<?php

namespace App\Http\Controllers;

use App\Models\ContactList;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactListController extends Controller
{
    public function index(): Response
    {
        $lists = ContactList::forUser(auth()->id())
            ->latest()
            ->paginate(10);

        return Inertia::render('ContactLists/Index', [
            'lists' => $lists,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ContactLists/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ContactList::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('contact-lists.index');
    }

    public function show(ContactList $contactList): Response
    {
        $this->authorizeList($contactList);

        $subscribers = $contactList->subscribers()
            ->latest()
            ->paginate(20);

        return Inertia::render('ContactLists/Show', [
            'list'        => $contactList,
            'subscribers' => $subscribers,
        ]);
    }

    public function destroy(ContactList $contactList)
    {
        $this->authorizeList($contactList);
        $contactList->delete();

        return redirect()->route('contact-lists.index');
    }

    // ── CSV Upload ────────────────────────────────────

    public function uploadForm(ContactList $contactList): Response
    {
        $this->authorizeList($contactList);

        return Inertia::render('ContactLists/Upload', [
            'list' => $contactList,
        ]);
    }

    public function uploadCsv(Request $request, ContactList $contactList)
    {
        $this->authorizeList($contactList);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');

        // Read header row
        $header = fgetcsv($handle);
        $header = array_map('strtolower', array_map('trim', $header));

        // Find column indexes
        $emailIndex     = array_search('email', $header);
        $firstNameIndex = array_search('first_name', $header);
        $lastNameIndex  = array_search('last_name', $header);

        if ($emailIndex === false) {
            return back()->withErrors(['csv_file' => 'CSV must have an "email" column.']);
        }

        $imported   = 0;
        $duplicates = 0;
        $invalid    = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $email = trim($row[$emailIndex] ?? '');

            // Skip empty or invalid emails
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalid++;
                continue;
            }

            // Skip duplicates within this list
            $exists = Subscriber::where('contact_list_id', $contactList->id)
                ->where('email', $email)
                ->exists();

            if ($exists) {
                $duplicates++;
                continue;
            }

            Subscriber::create([
                'user_id'         => auth()->id(),
                'contact_list_id' => $contactList->id,
                'email'           => $email,
                'first_name'      => $firstNameIndex !== false ? trim($row[$firstNameIndex] ?? '') : null,
                'last_name'       => $lastNameIndex !== false ? trim($row[$lastNameIndex] ?? '') : null,
                'status'          => 'subscribed',
                'subscribed_at'   => now(),
            ]);

            $imported++;
        }

        fclose($handle);

        // Update subscribers count
        $contactList->update([
            'subscribers_count' => $contactList->subscribers()->count(),
        ]);

        return redirect()->route('contact-lists.show', $contactList->id)
            ->with('success', "Import complete! {$imported} imported, {$duplicates} duplicates skipped, {$invalid} invalid emails skipped.");
    }

    private function authorizeList(ContactList $contactList): void
    {
        if ($contactList->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}