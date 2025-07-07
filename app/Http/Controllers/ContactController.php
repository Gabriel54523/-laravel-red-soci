<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of contacts
     */
    public function index()
    {
        $contacts = Auth::user()->contacts()->orderBy('name')->get();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created contact
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:contacts,phone_number,NULL,id,user_id,' . Auth::id(),
            'status' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contact = Auth::user()->contacts()->create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? '¡Hola! Estoy usando la aplicación de mensajería.',
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contacto creado exitosamente.');
    }

    /**
     * Display the specified contact
     */
    public function show(Contact $contact)
    {
        // Ensure user owns this contact
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact
     */
    public function edit(Contact $contact)
    {
        // Ensure user owns this contact
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified contact
     */
    public function update(Request $request, Contact $contact)
    {
        // Ensure user owns this contact
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:contacts,phone_number,' . $contact->id . ',id,user_id,' . Auth::id(),
            'status' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contact->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contacto actualizado exitosamente.');
    }

    /**
     * Remove the specified contact
     */
    public function destroy(Contact $contact)
    {
        // Ensure user owns this contact
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contacto eliminado exitosamente.');
    }
} 