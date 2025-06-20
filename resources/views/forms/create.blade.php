@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Create New Form</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('forms.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Form Title</label>
                <input type="text" name="form_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Form Description</label>
                <textarea name="form_description" class="form-control" rows="3"></textarea>
            </div>

            <h4>Form Fields</h4>
            <div id="fields-wrapper"></div>

            <button type="button" id="add-field" class="btn btn-secondary mb-3">+ Add Field</button>
            <br>
            <button type="submit" class="btn btn-primary">Create Form</button>
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
        let fieldIndex = 0;

        function createField(index) {
            let template = document.getElementById('field-template').innerHTML;
            template = template.replace(/__INDEX__/g, index);
            return $(template);
        }

        function handleFieldEvents($field) {
            $field.find('.field-type').on('change', function () {
                const isSelect = $(this).val() === 'select';
                $field.find('.field-options').toggleClass('d-none', !isSelect);
            }).trigger('change');

            $field.find('.remove-field').on('click', () => $field.remove());
        }

        $('#add-field').on('click', function () {
            console.log('Adding new field');
            const $newField = createField(fieldIndex++);
            $('#fields-wrapper').append($newField);
            handleFieldEvents($newField);
        });
    </script>
@endsection