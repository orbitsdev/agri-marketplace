<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    {{-- @dump($record) --}}

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <!-- Invoice summary -->
          <div class="lg:col-start-3 lg:row-end-1">
            <h2 class="sr-only">Summary</h2>
            <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-900/5">
              <dl class="flex flex-wrap">
                <div class="flex-auto pl-6 pt-6">
                  <dt class="text-sm/6 font-semibold text-gray-900">Amount</dt>
                  <dd class="mt-1 text-base font-semibold text-gray-900">$10,560.00</dd>
                </div>
                <div class="flex-none self-end px-6 pt-4">
                  <dt class="sr-only">Status</dt>
                  <dd class="rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-600 ring-1 ring-inset ring-green-600/20">Paid</dd>
                </div>
                <div class="mt-6 flex w-full flex-none gap-x-4 border-t border-gray-900/5 px-6 pt-6">
                  <dt class="flex-none">
                    <span class="sr-only">Client</span>
                    <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                    </svg>
                  </dt>
                  <dd class="text-sm/6 font-medium text-gray-900">Alex Curren</dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                  <dt class="flex-none">
                    <span class="sr-only">Due date</span>
                    <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path d="M5.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H6a.75.75 0 0 1-.75-.75V12ZM6 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H6ZM7.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H8a.75.75 0 0 1-.75-.75V12ZM8 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H8ZM9.25 10a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H10a.75.75 0 0 1-.75-.75V10ZM10 11.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V12a.75.75 0 0 0-.75-.75H10ZM9.25 14a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H10a.75.75 0 0 1-.75-.75V14ZM12 9.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V10a.75.75 0 0 0-.75-.75H12ZM11.25 12a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H12a.75.75 0 0 1-.75-.75V12ZM12 13.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V14a.75.75 0 0 0-.75-.75H12ZM13.25 10a.75.75 0 0 1 .75-.75h.01a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75H14a.75.75 0 0 1-.75-.75V10ZM14 11.25a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75h.01a.75.75 0 0 0 .75-.75V12a.75.75 0 0 0-.75-.75H14Z" />
                      <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
                    </svg>
                  </dt>
                  <dd class="text-sm/6 text-gray-500">
                    <time datetime="2023-01-31">January 31, 2023</time>
                  </dd>
                </div>
                <div class="mt-4 flex w-full flex-none gap-x-4 px-6">
                  <dt class="flex-none">
                    <span class="sr-only">Status</span>
                    <svg class="h-6 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M2.5 4A1.5 1.5 0 0 0 1 5.5V6h18v-.5A1.5 1.5 0 0 0 17.5 4h-15ZM19 8.5H1v6A1.5 1.5 0 0 0 2.5 16h15a1.5 1.5 0 0 0 1.5-1.5v-6ZM3 13.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Zm4.75-.75a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5h-3.5Z" clip-rule="evenodd" />
                    </svg>
                  </dt>
                  <dd class="text-sm/6 text-gray-500">Paid with MasterCard</dd>
                </div>
              </dl>
              <div class="mt-6 border-t border-gray-900/5 px-6 py-6">
                <a href="#" class="text-sm/6 font-semibold text-gray-900">Download receipt <span aria-hidden="true">&rarr;</span></a>
              </div>
            </div>
          </div>
    
          <!-- Invoice -->
          <div class="-mx-4 px-4 py-8 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-16">
            <h2 class="text-base font-semibold text-gray-900">Invoice</h2>
            <dl class="mt-6 grid grid-cols-1 text-sm/6 sm:grid-cols-2">
              <div class="sm:pr-4">
                <dt class="inline text-gray-500">Issued on</dt>
                <dd class="inline text-gray-700"><time datetime="2023-23-01">January 23, 2023</time></dd>
              </div>
              <div class="mt-2 sm:mt-0 sm:pl-4">
                <dt class="inline text-gray-500">Due on</dt>
                <dd class="inline text-gray-700"><time datetime="2023-31-01">January 31, 2023</time></dd>
              </div>
              <div class="mt-6 border-t border-gray-900/5 pt-6 sm:pr-4">
                <dt class="font-semibold text-gray-900">From</dt>
                <dd class="mt-2 text-gray-500"><span class="font-medium text-gray-900">Acme, Inc.</span><br>7363 Cynthia Pass<br>Toronto, ON N3Y 4H8</dd>
              </div>
              <div class="mt-8 sm:mt-6 sm:border-t sm:border-gray-900/5 sm:pl-4 sm:pt-6">
                <dt class="font-semibold text-gray-900">To</dt>
                <dd class="mt-2 text-gray-500"><span class="font-medium text-gray-900">Tuple, Inc</span><br>886 Walter Street<br>New York, NY 12345</dd>
              </div>
            </dl>
            <table class="mt-16 w-full whitespace-nowrap text-left text-sm/6">
              <colgroup>
                <col class="w-full">
                <col>
                <col>
                <col>
              </colgroup>
              <thead class="border-b border-gray-200 text-gray-900">
                <tr>
                  <th scope="col" class="px-0 py-3 font-semibold">Projects</th>
                  <th scope="col" class="hidden py-3 pl-8 pr-0 text-right font-semibold sm:table-cell">Hours</th>
                  <th scope="col" class="hidden py-3 pl-8 pr-0 text-right font-semibold sm:table-cell">Rate</th>
                  <th scope="col" class="py-3 pl-8 pr-0 text-right font-semibold">Price</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b border-gray-100">
                  <td class="max-w-0 px-0 py-5 align-top">
                    <div class="truncate font-medium text-gray-900">Logo redesign</div>
                    <div class="truncate text-gray-500">New logo and digital asset playbook.</div>
                  </td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">20.0</td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">$100.00</td>
                  <td class="py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700">$2,000.00</td>
                </tr>
                <tr class="border-b border-gray-100">
                  <td class="max-w-0 px-0 py-5 align-top">
                    <div class="truncate font-medium text-gray-900">Website redesign</div>
                    <div class="truncate text-gray-500">Design and program new company website.</div>
                  </td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">52.0</td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">$100.00</td>
                  <td class="py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700">$5,200.00</td>
                </tr>
                <tr class="border-b border-gray-100">
                  <td class="max-w-0 px-0 py-5 align-top">
                    <div class="truncate font-medium text-gray-900">Business cards</div>
                    <div class="truncate text-gray-500">Design and production of 3.5&quot; x 2.0&quot; business cards.</div>
                  </td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">12.0</td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">$100.00</td>
                  <td class="py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700">$1,200.00</td>
                </tr>
                <tr class="border-b border-gray-100">
                  <td class="max-w-0 px-0 py-5 align-top">
                    <div class="truncate font-medium text-gray-900">T-shirt design</div>
                    <div class="truncate text-gray-500">Three t-shirt design concepts.</div>
                  </td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">4.0</td>
                  <td class="hidden py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700 sm:table-cell">$100.00</td>
                  <td class="py-5 pl-8 pr-0 text-right align-top tabular-nums text-gray-700">$400.00</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th scope="row" class="px-0 pb-0 pt-6 font-normal text-gray-700 sm:hidden">Subtotal</th>
                  <th scope="row" colspan="3" class="hidden px-0 pb-0 pt-6 text-right font-normal text-gray-700 sm:table-cell">Subtotal</th>
                  <td class="pb-0 pl-8 pr-0 pt-6 text-right tabular-nums text-gray-900">$8,800.00</td>
                </tr>
                <tr>
                  <th scope="row" class="pt-4 font-normal text-gray-700 sm:hidden">Tax</th>
                  <th scope="row" colspan="3" class="hidden pt-4 text-right font-normal text-gray-700 sm:table-cell">Tax</th>
                  <td class="pb-0 pl-8 pr-0 pt-4 text-right tabular-nums text-gray-900">$1,760.00</td>
                </tr>
                <tr>
                  <th scope="row" class="pt-4 font-semibold text-gray-900 sm:hidden">Total</th>
                  <th scope="row" colspan="3" class="hidden pt-4 text-right font-semibold text-gray-900 sm:table-cell">Total</th>
                  <td class="pb-0 pl-8 pr-0 pt-4 text-right font-semibold tabular-nums text-gray-900">$10,560.00</td>
                </tr>
              </tfoot>
            </table>
          </div>
</div>
