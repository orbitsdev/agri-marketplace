<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100  relative" style="background: url('{{ asset('images/bg2.jpg') }}') center/cover no-repeat;">
    <div class="absolute inset-0 bg-gradient-to-b from-[#2c3e1f] via-[#3e5730] to-[#3e6129] opacity-25"></div>
    <div class="relative">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg relative">
        <h1>AGRI MARKET - Fresh From the Farm</h1>
        <p>Welcome to AGRI MARKET, your trusted marketplace for fresh agricultural products. Discover a wide range of grains, coffee, corn, rice, coconut, and more—sourced directly from local farmers and delivered with quality and care. Support local agriculture and enjoy healthy, farm-fresh food for your family.</p>
        {{ $slot }}
    </div>
</div>
