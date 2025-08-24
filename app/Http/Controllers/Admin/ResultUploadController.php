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
    public function form() { return view('admin.results.upload'); }

    // app/Http/Controllers/Admin/ResultUploadController.php
public function upload(Request $req)
{
    $req->validate(['file' => 'required|file|mimes:csv,txt']);

    $map = [
        'A+' => 4.00, 'A' => 4.00, 'A-' => 3.70,
        'B+' => 3.30, 'B' => 3.00, 'B-' => 2.70,
        'C+' => 2.30, 'C' => 2.00, 'C-' => 1.70,
        'D+' => 1.30, 'D' => 1.00,
        'E'  => 0.00, 'F' => 0.00,
    ];

    $file = fopen($req->file('file')->getRealPath(), 'r');
    if (!$file) return back()->with('error', 'Could not open file.');

    // Read and validate header exactly
    $header = fgetcsv($file);
    $expected = ['user_email','module_code','offering_year','offering_semester','academic_year','grade'];
    $normalizedHeader = array_map(fn($h) => strtolower(trim($h ?? '')), $header ?: []);
    if ($normalizedHeader !== $expected) {
        fclose($file);
        return back()->with('error', 'Invalid header. Expected: '.implode(',', $expected));
    }

    $ok = 0; $skipped = 0; $errors = []; $line = 1; // header = line 1

    while (($row = fgetcsv($file)) !== false) {
        $line++;

        // Skip empty lines
        if (count(array_filter($row, fn($v) => trim((string)$v) !== '')) === 0) continue;

        // Read + trim
        [$email,$code,$year,$sem,$acy,$grade] = array_pad($row, 6, null);
        $email = trim((string)$email);
        $code  = strtoupper(preg_replace('/\s+/', '', (string)$code)); // remove spaces in codes like "SWST 31022"
        $year  = (int) trim((string)$year);
        $sem   = (int) trim((string)$sem);
        $acy   = trim((string)$acy);
        $grade = strtoupper(trim((string)$grade));

        // Validate per-field and collect errors instead of crashing
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $skipped++; $errors[] = "Line $line: invalid email '$email'"; continue;
        }
        if (!$year || !$sem) {
            $skipped++; $errors[] = "Line $line: missing year/semester"; continue;
        }
        if (!isset($map[$grade])) {
            $skipped++; $errors[] = "Line $line: unknown grade '$grade'"; continue;
        }

        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) { $skipped++; $errors[] = "Line $line: user not found '$email'"; continue; }

        // find module by code (normalize spaces)
        $module = \App\Models\Module::whereRaw("REPLACE(UPPER(code),' ','') = ?", [$code])->first();
        if (!$module) { $skipped++; $errors[] = "Line $line: module not found for code '$code'"; continue; }

        $offering = \App\Models\ModuleOffering::where('module_id', $module->id)
            ->where('year', $year)
            ->where('semester', $sem)
            ->first();

        if (!$offering) {
            $skipped++; $errors[] = "Line $line: offering not found for {$module->code} (Y{$year} S{$sem})";
            continue;
        }

        \App\Models\Result::updateOrCreate(
            ['user_id' => $user->id, 'module_offering_id' => $offering->id],
            ['academic_year' => $acy, 'grade' => $grade, 'grade_points' => $map[$grade]]
        );
        $ok++;
    }
    fclose($file);

    return back()
        ->with('success', "Imported: $ok  |  Skipped: $skipped")
        ->with('details', $errors);
}

}

