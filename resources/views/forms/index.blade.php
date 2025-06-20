@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Dynamic Forms</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($forms->count())
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Public URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forms as $index => $form)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $form->form_title }}</td>
                            <td>{{ $form->form_description }}</td>
                            <td>{{ $form->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="#" class="copy-link" data-url="{{ route('form.public.show', $form->id) }}">Copy</a>
                            </td>
                            <td>
                                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('forms.destroy', $form->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure to delete this form?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info text-center">No forms created yet.</div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('forms.create') }}" class="btn btn-primary">+ Create New Form</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.copy-link').on('click', function (e) {
                e.preventDefault();
                const url = $(this).data('url');

                // Create a temporary input, copy the text, then remove it
                const tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(url).select();
                document.execCommand('copy');
                tempInput.remove();

                alert('URL copied to clipboard!');
            });
        });
    </script>
@endsection