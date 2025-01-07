<div>
    <x-buyer-layout>
        <div class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
            <div class="border-b border-gray-200 pb-10 pt-24">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">My Address</h1>
                <p class="mt-4 text-base text-gray-500">Manage your saved addresses for faster checkouts and seamless order deliveries. Add, update, or remove your addresses with ease.</p>

                <!-- Add Button -->
                <div class="mt-6">
                    <div class="mt-8 flex flex-1 flex-col justify-end">

                        {{ $this->addAddressAction }}
                    </div>
                </div>
            </div>

            <ul role="list" class="-mb-8 mt-8">

                @forelse ($addresses as $address)
                <li>

                    <div class="relative pb-8">
                      <div class="relative flex items-start space-x-3">

                        <div class="min-w-0 flex-1">
                          <div>
                            <div class="text-sm">
                              <a href="#" class="font-medium text-gray-900">Jason Meyers</a>
                            </div>
                            <p class="mt-0.5 text-sm text-gray-500">Commented 2h ago</p>
                          </div>
                          <div class="mt-2 text-sm text-gray-700">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tincidunt nunc ipsum tempor purus vitae id. Morbi in vestibulum nec varius. Et diam cursus quis sed purus nam. Scelerisque amet elit non sit ut tincidunt condimentum. Nisl ultrices eu venenatis diam.</p>
                          </div>
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
