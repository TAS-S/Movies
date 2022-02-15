<section class="container mx-auto p-6 font-mono">
    <div class="w-full flex mb-4 p-2 justify-end">
        <form class="flex space-x-4 shadow bg-white rounded-md m-2 p-2">
            <div class="p-1 flex items-center">
                <label for="tmdb_id_g" class="block text-sm font-medium text-gray-700 mr-4 pr-4">Genre TDMB ID</label>
                <div class="relative rounded-md shadow-sm">
                    <input wire:model="tmdbId" id="tmdbId" name="tmdbId" class="px-3 py-2 border border-gray-500 rounded p-5" placeholder="Genre ID">
                </div>
            </div>
            <div class="p-1">
                <x-jet-button type="button" wire:click="generateGenre" class="inline-flex items-center justify-center py-2 px-4 border border-transparent text-base leading-6 font-medium rounded bg-green-700">
                    <span>Generate</span>
                </x-m-button>
            </div>
        </form>
    </div>
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
      <div class="w-full overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
              <th class="px-4 py-3">Title</th>
              <th class="px-4 py-3">Slug</th>
              <th class="px-4 py-3">Manage</th>
            </tr>
          </thead>
          <tbody class="bg-white">

            @foreach ($genres as $genre)

                <tr class="text-gray-700">
                <td class="px-4 py-3 border">
                    {{ $genre->title }}
                </td>
                <td class="px-4 py-3 text-ms font-semibold border">{{ $genre->slug }}</td>
                <td class="px-4 py-3 text-sm border">
                    <x-m-button wire:click="showEditModal({{ $genre->id }})" class="mr-2 bg-green-500 hover:bg-green-700">Edit</x-m-button>
                    <x-m-button wire:click="deleteGenre({{ $genre->id }})" class="bg-red-500 hover:bg-red-700">Delete</x-m-button>
                </td>
                </tr>

            @endforeach

          </tbody>
        </table>
        <div class="m-2 p-2">
            {{ $genres->links() }}
        </div>
      </div>
    </div>
    <x-jet-dialog-modal wire:model="showGenreModal">
        <x-slot name="title">Update Genre</x-slot>
        <x-slot name="content">
            <div class="mt-10 sm:mt-0">
                  <div class="mt-5 md:mt-0 md:col-span-2">
                    <form>
                      <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                          <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                              <label for="first-name" class="block text-sm font-medium text-gray-700">Title</label>
                              <input wire:model="title" type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                @error('title')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                      </div>
                    </form>
                  </div>
              </div>
        </x-slot>
        <x-slot name="footer">
            <x-m-button wire:click="closeGenreModal" class="mr-2 bg-red-500 hover:bg-red-700">Cancel</x-m-button>
            <x-m-button wire:click="updateGenre">Update</x-m-button>
        </x-slot>
    </x-jet-dialog-modal>
  </section>
