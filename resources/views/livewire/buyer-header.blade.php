<header class="relative bg-white">
    <p class="flex h-10 items-center justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-4 text-sm font-medium text-white sm:px-6 lg:px-8">Get free delivery on orders over $100</p>

    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="border-b border-gray-200">
        <div class="flex h-16 items-center">
          <!-- Mobile menu toggle, controls the 'mobileMenuOpen' state. -->
          <button type="button" class="relative rounded-md bg-white p-2 text-gray-400 lg:hidden">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open menu</span>
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
          </button>

       

          <!-- Flyout menus -->
          <div class="hidden lg:ml-8 lg:block lg:self-stretch">
            <div class="flex h-full space-x-8">
              <div class="flex">
                <div class="relative flex">
                  <!-- Item active: "border-indigo-600 text-indigo-600", Item inactive: "border-transparent text-gray-700 hover:text-gray-800" -->
                  <button type="button" class="relative z-10 -mb-px flex items-center border-b-2 border-transparent pt-px text-sm font-medium text-gray-700 transition-colors duration-200 ease-out hover:text-gray-800" aria-expanded="false">Women</button>
                </div>

                
                
              </div>
             

              <a href="#" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">Company</a>
              <a href="#" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">Stores</a>
            </div>
          </div>

          <div class="ml-auto flex items-center">
            <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
             
              <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-800">{{Auth::user()->full_name}}</a>
            </div>

           

    
           
            <div class="ml-4 flow-root lg:ml-6">
              <a href="#" class="group -m-2 flex items-center p-2">
                <svg class="size-6 shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800">0</span>
                <span class="sr-only">items in cart, view bag</span>
              </a>
            </div>
            <div class="ml-4 relative" x-data="{ open: false }">
                <button @click="open = ! open" class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                </button>

                <div 
                    x-show="open" 
                    @click.away="open = false" 
                    class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="transform opacity-0 scale-95" 
                    x-transition:enter-end="transform opacity-100 scale-100" 
                    x-transition:leave="transition ease-in duration-75" 
                    x-transition:leave-start="transform opacity-100 scale-100" 
                    x-transition:leave-end="transform opacity-0 scale-95"
                >
                    <div class="py-1">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>