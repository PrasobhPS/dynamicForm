@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Edit Form</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('forms.update', $form->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Form Title</label>
                <input type="text" name="form_title" class="form-control" value="{{ old('form_title', $form->form_title) }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Form Description</label>
                <textarea name="form_description" class="form-control"
                    rows="3">{{ old('form_description', $form->form_description) }}</textarea>
            </div>

            <h4>Form Fields</h4>
            <div id="fields-wrapper">
                @foreach ($fields as $i => $field)
                    <div class="card p-3 mb-3 border field-row">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Label</label>
                                <input type="text" class="form-control field-label" name="fields[{{ $i }}][label]"
                                    value="{{ old("fields.$i.label", $field->label) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Type</label>
                                <select class="form-control field-type" name="fields[{{ $i }}][type]" required>
                                    <option value="text" {{ $field->type === 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="number" {{ $field->type === 'number' ? 'selected' : '' }}>Number</option>
                                    <option value="select" {{ $field->type === 'select' ? 'selected' : '' }}>Dropdown</option>
                                </select>
                            </div>
                            @php
                                $optionsArray = is_array($field->options)
                                    ? $field->options
                                    : (json_decode($field->options, true) ?? []);
                            @endphp

                            <div class="col-md-4 field-options {{ $field->type !== 'select' ? 'd-none' : '' }}">
                                <label>Options (comma separated)</label>
                                <input type="text" class="form-control" name="fields[{{ $i }}][options]"
                                    value="{{ old("fields.$i.options", implode(', ', $optionsArray)) }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-field" class="btn btn-secondary mb-3">+ Add Field</button>
            <br>
            <button type="submit" class="btn btn-primary">Update Form</button>
        </form>

        <!-- Hidden template -->
        <template id="field-template">
            <div class="card p-3 mb-3 border field-row">
                <div class="row">
                    <div class="col-md-4">
                        <label>Label</label>
                        <input type="text" class="form-control field-label" name="fields[__INDEX__][label]" required>
                    </div>
                    <div class="col-md-3">
                        <label>Type</label>
                        <select class="form-control field-type" name="fields[__INDEX__][type]" required>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="select">Dropdown</option>
                        </select>
                    </div>
                    <div class="col-md-4 field-options d-none">
                        <label>Options (comma separated)</label>
                        <input type="text" class="form-control" name="fields[__INDEX__][options]">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection

@section('scripts')
    <script>
        let fieldIndex = {{ count($fields) }};

        function createField(index) {
            let template = document.getElementById('field-template').innerHTML;
            return $(template.replace(/__INDEX__/g, index));
        }

        function handleFieldEvents($field) {
            $field.find('.field-type').on('change', function () {
                const isSelect = $(this).val() === 'select';
                $field.find('.field-options').toggleClass('d-none', !isSelect);
            }).trigger('change');

            $field.find('.remove-field').on('click', () => $field.remove());
        }

        $('#add-field').on('click', function () {
            const $newField = createField(fieldIndex++);
            $('#fields-wrapper').append($newField);
            handleFieldEvents($newField);
        });

        $('#fields-wrapper .field-row').each(function () {
            handleFieldEvents($(this));
        });
    </script>
@endsection