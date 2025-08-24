@extends('layouts.web_layout')

@section('title','Upload Results')

@section('content')
  <div class="mt-4" style="max-width:640px">
    <h3 class="mb-3">Upload Results (CSV)</h3>

    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('details')) <pre class="small">{{ implode("\n",(array)session('details')) }}</pre> @endif

    <form method="post" enctype="multipart/form-data" action="{{ url('/admin/results/upload') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">CSV File</label>
        <input type="file" name="file" class="form-control" accept=".csv" required>
        <div class="form-text">Headers: user_email,module_code,offering_year,offering_semester,academic_year,grade</div>
      </div>
      <button class="btn btn-primary">Upload</button>
    </form>
  </div>
@endsection
