<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:shadow-[0_6px_20px_rgba(79,70,229,0.23)] hover:-translate-y-0.5 ease-in-out duration-200 w-full']) }}>
    {{ $slot }}
</button>
