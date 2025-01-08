<div>
    <x-buyer-layout>

        <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
            <div>

                <p class="mt-2 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Order Summary</p>
                <p class="mt-2 text-base text-gray-500">Thank you for your order! Below is a summary of your purchase.</p>

            </div>

            <div class="mt-8"></div>
            @forelse ($groupedPendingOrders as $farmerId => $farmerOrders)
                @php
                    $farmerName = $farmerOrders->first()->farmer->farm_name ?? 'Unknown Farmer';
                @endphp

                <div class="mb-6 bg-white rounded">
                    <h3
                        class="text-lg px-6 font-semibold text-white mb-4 bg-gradient-to-r from-eucalyptus-700 to-eucalyptus-600 rounded-t">
                        {{ $farmerName }}
                    </h3>

                    <ul>
                        @foreach ($farmerOrders as $order)
                        {{-- @dump($order) --}}

                        <div class="">


                            <dl class="mt-16 text-sm font-medium">
                              <dt class="text-gray-900">Tracking number</dt>
                              <dd class="mt-2 text-indigo-600">51547878755545848512</dd>
                            </dl>

                            <ul role="list" class="mt-6 divide-y divide-gray-200 border-t border-gray-200 text-sm font-medium text-gray-500">
                              <li class="flex space-x-6 py-6">
                                <img src="https://tailwindui.com/plus/img/ecommerce-images/confirmation-page-06-product-01.jpg" alt="Model wearing men&#039;s charcoal basic tee in large." class="size-24 flex-none rounded-md bg-gray-100 object-cover">
                                <div class="flex-auto space-y-1">
                                  <h3 class="text-gray-900">
                                    <a href="#">Basic Tee</a>
                                  </h3>
                                  <p>Charcoal</p>
                                  <p>L</p>
                                </div>
                                <p class="flex-none font-medium text-gray-900">$36.00</p>
                              </li>

                              <!-- More products... -->
                            </ul>

                            <dl class="space-y-6 border-t border-gray-200 pt-6 text-sm font-medium text-gray-500">
                              <div class="flex justify-between">
                                <dt>Subtotal</dt>
                                <dd class="text-gray-900">$72.00</dd>
                              </div>

                              <div class="flex justify-between">
                                <dt>Shipping</dt>
                                <dd class="text-gray-900">$8.00</dd>
                              </div>

                              <div class="flex justify-between">
                                <dt>Taxes</dt>
                                <dd class="text-gray-900">$6.40</dd>
                              </div>

                              <div class="flex items-center justify-between border-t border-gray-200 pt-6 text-gray-900">
                                <dt class="text-base">Total</dt>
                                <dd class="text-base">$86.40</dd>
                              </div>
                            </dl>

                            <dl class="mt-16 grid grid-cols-2 gap-x-4 text-sm text-gray-600">
                              <div>
                                <dt class="font-medium text-gray-900">Shipping Address</dt>
                                <dd class="mt-2">
                                  <address class="not-italic">
                                    <span class="block">Kristin Watson</span>
                                    <span class="block">7363 Cynthia Pass</span>
                                    <span class="block">Toronto, ON N3Y 4H8</span>
                                  </address>
                                </dd>
                              </div>
                              <div>
                                <dt class="font-medium text-gray-900">Payment Information</dt>
                                <dd class="mt-2 space-y-2 sm:flex sm:space-x-4 sm:space-y-0">
                                  <div class="flex-none">
                                    <svg aria-hidden="true" width="36" height="24" viewBox="0 0 36 24" class="h-6 w-auto">
                                      <rect width="36" height="24" rx="4" fill="#224DBA" />
                                      <path d="M10.925 15.673H8.874l-1.538-6c-.073-.276-.228-.52-.456-.635A6.575 6.575 0 005 8.403v-.231h3.304c.456 0 .798.347.855.75l.798 4.328 2.05-5.078h1.994l-3.076 7.5zm4.216 0h-1.937L14.8 8.172h1.937l-1.595 7.5zm4.101-5.422c.057-.404.399-.635.798-.635a3.54 3.54 0 011.88.346l.342-1.615A4.808 4.808 0 0020.496 8c-1.88 0-3.248 1.039-3.248 2.481 0 1.097.969 1.673 1.653 2.02.74.346 1.025.577.968.923 0 .519-.57.75-1.139.75a4.795 4.795 0 01-1.994-.462l-.342 1.616a5.48 5.48 0 002.108.404c2.108.057 3.418-.981 3.418-2.539 0-1.962-2.678-2.077-2.678-2.942zm9.457 5.422L27.16 8.172h-1.652a.858.858 0 00-.798.577l-2.848 6.924h1.994l.398-1.096h2.45l.228 1.096h1.766zm-2.905-5.482l.57 2.827h-1.596l1.026-2.827z" fill="#fff" />
                                    </svg>
                                    <p class="sr-only">Visa</p>
                                  </div>
                                  <div class="flex-auto">
                                    <p class="text-gray-900">Ending with 4242</p>
                                    <p>Expires 12 / 21</p>
                                  </div>
                                </dd>
                              </div>
                            </dl>

                            <div class="mt-16 border-t border-gray-200 py-6 text-right">
                              <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Continue Shopping
                                <span aria-hidden="true"> &rarr;</span>
                              </a>
                            </div>
                          </div>
                        {{-- <li class="flex py-6 sm:py-10">
                            <div class="shrink-0">
                              <img src="https://tailwindui.com/plus/img/ecommerce-images/shopping-cart-page-01-product-01.jpg" alt="Front of men&#039;s Basic Tee in sienna." class="size-24 rounded-md object-cover sm:size-48">
                            </div>

                            <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                              <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                <div>
                                  <div class="flex justify-between">
                                    <h3 class="text-sm">
                                      <a href="#" class="font-medium text-gray-700 hover:text-gray-800">Basic Tee</a>
                                    </h3>
                                  </div>
                                  <div class="mt-1 flex text-sm">
                                    <p class="text-gray-500">Sienna</p>
                                    <p class="ml-4 border-l border-gray-200 pl-4 text-gray-500">Large</p>
                                  </div>
                                  <p class="mt-1 text-sm font-medium text-gray-900">$32.00</p>
                                </div>


                              </div>

                              <p class="mt-4 flex space-x-2 text-sm text-gray-700">
                                <svg class="size-5 shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                </svg>
                                <span>In stock</span>
                              </p>
                            </div>
                          </li> --}}
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-center text-gray-500">No pending orders found.</p>
            @endforelse
        </div>

    </x-buyer-layout>
    {{-- Because she competes with no one, no one can compete with her. --}}
</div>
