<div class="">
    <x-buyer-layout>
        <div class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
            <div class="flex ">

                <div class="border-b border-white pb-10 pt-24 ">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">My Address</h1>
                    <p class="mt-4 text-base text-gray-500">Manage your saved addresses for faster checkouts and seamless order deliveries. Add, update, or remove your addresses with ease.</p>

                    <!-- Add Button -->

                </div>
                <div class="mt-6">
                    {{ $this->addAddressAction }}

                </div>
            </div>

            <ul role="list" class="-mb-8 mt-8">

                @forelse ($addresses as  $key=> $address)
                <li>
                    <div class="relative py-4 {{ $loop->last ? '' : 'border-b' }}">
                        <div class="relative flex items-start space-x-3">
                            <!-- Icon or Avatar -->
                            <div class="flex-shrink-0">
                                <div class="relative h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white shadow-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 10.487a49.255 49.255 0 0 1-.214 2.276c-.156 1.203-.338 2.409-.544 3.613-.056.324-.113.647-.171.97-.131.688-.273 1.373-.425 2.054a1.5 1.5 0 1 1-2.963-.5c.15-.68.292-1.366.425-2.054.058-.322.115-.646.171-.97.206-1.204.388-2.41.544-3.613.071-.554.138-1.11.214-1.662l.001-.012A7.5 7.5 0 1 1 16.862 10.487z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $address->street }}, {{ $address->barangay }}, {{ $address->city_municipality }}, {{ $address->province }}, {{ $address->region }}
                                    </p>
                                    @if($address->is_default)
                                        <!-- Default Badge -->
                                        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                            <svg class="size-1.5 fill-green-500" viewBox="0 0 6 6" aria-hidden="true">
                                                <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Default
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-500">ZIP Code: {{ $address->zip_code }}</p>
                            </div>

                            <!-- Edit Button -->
                            <div>
                                {{ ($this->editAddress)(['record' => $address->id]) }}
                            </div>
                            <div>
                                {{ ($this->deleteLocationAction)(['record' => $address->id]) }}
                            </div>
                        </div>
                    </div>
                </li>
@empty
    <li class="py-6 sm:py-10 text-center">
        <div class="flex flex-col items-center space-y-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="h-16 w-16 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2.25c4.556 0 8.25 3.694 8.25 8.25s-3.694 8.25-8.25 8.25-8.25-3.694-8.25-8.25S7.444 2.25 12 2.25zm0 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0 3a3.75 3.75 0 0 1-3.75 3.75h7.5A3.75 3.75 0 0 1 12 14.25z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700">No addresses found</h3>
            <p class="text-gray-500">
                It seems like you haven’t added any addresses yet. Add your address to make your checkout easier!
            </p>
            <!-- Add a button to allow the user to add a new address -->
        </div>
    </li>
@endforelse

            </ul>
          </div>


     </x-buyer-layout>
     <x-filament-actions::modals />
</div>
