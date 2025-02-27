<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Store a newly created form.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'background_color' => 'nullable|string|max:255',
            'font_family' => 'nullable|string|max:255',
            'form_labels' => 'required|boolean',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.options' => 'nullable|array',
        ]);

        // Create the form
        $form = Form::create([
            'title' => $validatedData['name'],
            'description' => $validatedData['name'], // Consider renaming if needed
            'background_color' => $validatedData['background_color'],
            'font_family' => $validatedData['font_family'],
            'form_labels' => $validatedData['form_labels'],
        ]);

        // Create form fields
        $fields = array_map(function ($field) use ($form) {
            return [
                'form_id' => $form->id,
                'type' => $field['type'],
                'label' => $field['label'],
                'options' => isset($field['options']) ? json_encode($field['options']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $validatedData['fields']);

        FormField::insert($fields);

        return response()->json([
            'message' => 'Form created successfully',
            'form' => $form->load('fields'),
        ], 201);
    }

    /**
     * Retrieve all forms with their associated fields.
     */
    public function getAllForms(): JsonResponse
    {
        $forms = Form::with('fields')->get();

        if ($forms->isEmpty()) {
            return response()->json(['error' => 'No forms found'], 404);
        }

        return response()->json($forms);

    }
}
