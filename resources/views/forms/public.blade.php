@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h2>{{ $form->form_title }}</h2>
        <p>{{ $form->form_description }}</p>

        <form method="POST" action="{{ route('form.public.store', $form->id) }}">
            @csrf
            @foreach ($form->fields as $field)
                <div class="mb-3">
                    <label>{{ $field->label }}</label>

                    @if ($field->type === 'select')
                        <select name="field_{{ $field->label }}" class="form-control">
                            @foreach (json_decode($field->options, true) as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $field->type }}" name="field_{{ $field->label }}" class="form-control">
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection