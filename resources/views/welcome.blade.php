<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <title>Notes</title>
    </head>
    <body class="min-h-full bg-grey-lighter text-grey-darkest">
        <div id="app">
            <div class="bg-blue-light text-white mb-4 shadow">
                <div class="container mx-auto flex justify-between items-center p-4">
                    <p>Notes: {{ auth()->user()->name }}</p>
                    <p class="italic text-sm">BrandBoom Code Challenge</p>
                </div>
            </div>
            <div class="container mx-auto w-full p-4" >
                <div class="w-full flex justify-center mb-8">
                    <div class="w-full md:w-2/3 lg:w-1/2">
                        <new-note @note-taken="addNoteToList"></new-note>
                    </div>
                </div>
                <div class="w-full">
                    <div class="flex flex-wrap -mx-4">
                        <note v-for="(note, index) in notes"
                              :key="note.created_at"
                              :note="note"
                              class="w-1/3 mb-8"
                              @created="handleNoteCreation"
                              @updated="handleNoteUpdate"
                              @deleted="handleNoteDeletion"
                        ></note>
                    </div>
                </div>
            </div>
            <portal-target name="modals"></portal-target>
        </div>

        <script>
          window.Laravel = @json(['csrfToken' => csrf_token()]);
          var notes = @json($notes);
        </script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
