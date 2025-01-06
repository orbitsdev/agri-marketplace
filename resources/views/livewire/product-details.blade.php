<div>
    <x-buyer-layout>


        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:grid lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
              <!-- Product details -->
              <div class="lg:max-w-lg lg:self-end">
                <nav aria-label="Breadcrumb">
                  <ol role="list" class="flex items-center space-x-2">
                    <li>
                      <div class="flex items-center text-sm">
                        <a href="#" class="font-medium text-gray-500 hover:text-gray-900">{{$product->farmer->farm_name}} Farm</a>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="ml-2 size-5 shrink-0 text-gray-300">
                          <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center text-sm">
                        <a href="#" class="font-medium text-gray-500 hover:text-gray-900">{{
                            $product->farmer->user->full_name
                            }}</a>
                      </div>
                    </li>
                  </ol>
                </nav>

                <div class="mt-4">
                  <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{$product->product_name}}</h1>
                </div>

                <section aria-labelledby="information-heading" class="mt-4">
                  <h2 id="information-heading" class="sr-only">Product information</h2>

                  <div class="flex items-center">
                    <p class="text-lg text-gray-900 sm:text-xl">₱ {{$product->price}}</p>

                    <div class="ml-4 border-l border-gray-300 pl-4">
                      <h2 class="sr-only">Code</h2>
                      <div class="flex items-center">

                        <p class="ml-2 text-sm text-gray-500">{{$product->code}}</p>
                      </div>
                    </div>
                  </div>



                  <div class="mt-4 space-y-6">
                    <div class="product custom-prose">
                        <p>
                            @markdown($product->description)
                        </p>
                    </div>
                </div>


                </section>
              </div>

              <!-- Product image -->
              <div class="mt-10 lg:col-start-2 lg:row-span-2 lg:mt-0 lg:self-center">
                <a href="{{$product->getImage()}}" target="_blank">

                    <img src="{{$product->getImage()}}" alt="{{$product->product_name}}" class="aspect-square w-full rounded-lg object-cover">
                </a>
              </div>

              <!-- Product form -->
              <div class="mt-10 lg:col-start-1 lg:row-start-2 lg:max-w-lg lg:self-start">
                <section aria-labelledby="options-heading">
                  <h2 id="options-heading" class="sr-only">Product options</h2>


                    <div class="mt-8 flex flex-1 flex-col justify-end">
                      {{-- <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Add to bag</button> --}}
                      {{ ($this->addToCartAction)(['record' => $product->id]) }}
                    </div>
                    <div class="mt-6 text-center">
                      <a href="#" class="group inline-flex text-base font-medium">
                        <svg class="mr-2 size-6 shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <span class="text-gray-500 hover:text-gray-700">Lifetime Guarantee</span>
                      </a>
                    </div>
                </section>
              </div>
            </div>
          </div>

    </x-buyer-layout>
    <x-filament-actions::modals />
</div>
