<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;
use App\Models\ModuleOffering;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultUploadController extends Controller
{
    public function form()
    {
        // Only used if you render a Blade form at /admin/results/upload
        return view('admin.results.upload');
    }

    public function upload(Request $req)
    {
        $req->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $gradeMap = [
            'A+' => 4.00, 'A' => 4.00, 'A-' => 3.70,
            'B+' => 3.30, 'B' => 3.00, 'B-' => 2.70,
            'C+' => 2.30, 'C' => 2.00, 'C-' => 1.70,
            'D+' => 1.30, 'D' => 1.00,
            'E'  => 0.00, 'F' => 0.00,
        ];

        $handle = fopen($req->file('file')->getRealPath(), 'r');
        if (!$handle) {
            return response()->json([
                'message' => 'Could not open file.'
            ], 400);
        }

        // Header check
        $header = fgetcsv($handle);
        $expected = ['user_email','module_code','offering_year','offering_semester','academic_year','grade'];
        $normalized = array_map(fn($h) => strtolower(trim($h ?? '')), $header ?: []);
        if ($normalized !== $expected) {
            fclose($handle);
            return response()->json([
                'message' => 'Invalid header.',
                'expected' => implode(',', $expected),
                'received' => implode(',', $normalized),
            ], 422);
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $line     = 1; // header = line 1

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            // Skip fully empty line
            if (count(array_filter($row, fn($v) => trim((string)$v) !== '')) === 0) {
                continue;
            }

            // Read + normalize
            [$email,$code,$year,$sem,$acy,$grade] = array_pad($row, 6, null);
            $email = trim((string)$email);
            $code  = strtoupper(preg_replace('/\s+/', '', (string)$code)); // remove spaces inside codes
            $year  = (int) trim((string)$year);
            $sem   = (int) trim((string)$sem);
            $acy   = trim((string)$acy);
            $grade = strtoupper(trim((string)$grade));

            // Validate fields
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++; $errors[] = "Line $line: invalid email '$email'"; continue;
            }
            if (!$year || !$sem) {
                $skipped++; $errors[] = "Line $line: missing year/semester"; continue;
            }
            if (!isset($gradeMap[$grade])) {
                $skipped++; $errors[] = "Line $line: unknown grade '$grade'"; continue;
            }

            $user = User::where('email', $email)->first();
            if (!$user) { $skipped++; $errors[] = "Line $line: user not found '$email'"; continue; }

            $module = Module::whereRaw("REPLACE(UPPER(code),' ','') = ?", [$code])->first();
            if (!$module) { $skipped++; $errors[] = "Line $line: module not found for code '$code'"; continue; }

            $offering = ModuleOffering::where('module_id', $module->id)
                ->where('year', $year)
                ->where('semester', $sem)
                ->first();

            if (!$offering) {
                $skipped++; $errors[] = "Line $line: offering not found for {$module->code} (Y{$year} S{$sem})";
                continue;
            }

            Result::updateOrCreate(
                ['user_id' => $user->id, 'module_offering_id' => $offering->id],
                ['academic_year' => $acy, 'grade' => $grade, 'grade_points' => $gradeMap[$grade]]
            );
            $imported++;
        }

        fclose($handle);

        return response()->json([
            'message'  => 'Upload processed.',
            'imported' => $imported,
            'skipped'  => $skipped,
            'errors'   => $errors,
        ]);
    }
}
