<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-eucalyptus-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-eucalyptus-700 focus:bg-eucalyptus-700 active:bg-eucalyptus-900 focus:outline-none focus:ring-2 focus:ring-eucalyptus-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
