<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormField;
use App\Models\Form;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all forms from the database
        $forms = Form::all();

        // Return the view with the forms data
        return view('forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return the view to create a new form
        return view('forms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'form_title' => 'required|string|max:255',
            'form_description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|in:text,number,select',
            'fields.*.options' => 'nullable|string',
        ]);

        foreach ($request->fields as $index => $field) {
            if ($field['type'] === 'select' && empty(trim($field['options'] ?? ''))) {
                return redirect()->back()
                    ->withErrors(["fields.$index.options" => "Options are required for select type fields."])
                    ->withInput();
            }
        }

        // Create a new form instance and save it to the database
        $form = new Form();
        $form->form_title = $request->input('form_title');
        $form->form_description = $request->input('form_description');
        $form->save();

        // Loop through the fields and create each one
        foreach ($request->fields as $field) {
            $formField = new FormField();
            $formField->form_id = $form->id;
            $formField->label = $field['label'];
            $formField->type = $field['type'];
            $formField->options = isset($field['options']) ? json_encode(explode(',', $field['options'])) : null;
            $formField->save();
        }

        // Redirect to the forms index with a success message
        return redirect()->route('forms.index')->with('success', 'Form created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the form by ID
        $form = Form::findOrFail($id);

        // Fetch the fields associated with the form
        $fields = $form->fields;


        return view('forms.edit', compact('form', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'form_title' => 'required|string|max:255',
            'form_description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|in:text,number,select',
            'fields.*.options' => 'nullable|string',
        ]);

        foreach ($request->fields as $index => $field) {
            if ($field['type'] === 'select' && empty(trim($field['options'] ?? ''))) {
                return redirect()->back()
                    ->withErrors(["fields.$index.options" => "Options are required for select type fields."])
                    ->withInput();
            }
        }

        // Find the form by ID and update it
        $form = Form::findOrFail($id);
        $form->form_title = $request->input('form_title');
        $form->form_description = $request->input('form_description');
        $form->save();

        $form->fields()->delete();

        foreach ($request->fields as $field) {
            $form->fields()->create([
                'label' => $field['label'],
                'type' => $field['type'],
                'options' => $field['type'] === 'select'
                    ? json_encode(explode(',', $field['options']))
                    : null,
            ]);
        }

        // Redirect to the forms index with a success message
        return redirect()->route('forms.index')->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the form by ID
        $form = Form::findOrFail($id);

        // Delete the form and its associated fields
        $form->fields()->delete();
        $form->delete();

        // Redirect to the forms index with a success message
        return redirect()->route('forms.index')->with('success', 'Form deleted successfully.');
    }
}
