<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Jobs\NotifyFormCreated;

class PublicFormController extends Controller
{
    public function show($id)
    {
        $form = Form::with('fields')->findOrFail($id);

        return view('forms.public', compact('form'));
    }

    public function store(Request $request, $id)
    {
        $form = Form::with('fields')->findOrFail($id);
        $responses = collect($request->all())
            ->filter(function ($value, $key) {
                return str_starts_with($key, 'field_');
            });

        NotifyFormCreated::dispatch($form, $responses);
        \Log::info("Form '{$form->form_title}' submitted.", $responses->toArray());


        return redirect()->back()->with('success', 'Your responses have been submitted successfully!');
    }
}
