<div class="flex gap-4">
    <label for="{{ $getKey() }}" class="relative rounded-full">
        <input
            id="{{ $getKey() }}"
            name="{{ $getName($locale) }}[]"
            type="file"
            class="absolute inset-0 w-full opacity-0 cursor-pointer pointer-events-auto peer"
        />

        <div class="flex items-center gap-2 px-4 py-2 transition-all duration-75 ease-in-out rounded-full bg-grey-100 group hover:bg-primary-50 peer-focus:ring-1 peer-focus:ring-primary-500">
            <svg class="w-6 h-6 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>
            <span class="transition-all duration-75 ease-in-out group-hover:text-primary-500">Upload nieuwe bestanden</span>
        </div>
    </label>

    <div class="flex items-center gap-2 px-4 py-2 transition-all duration-75 ease-in-out rounded-full bg-grey-100 group hover:bg-primary-50">
        <svg class="w-5 h-5 m-0.5 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
        <span class="transition-all duration-75 ease-in-out group-hover:text-primary-500">Kies bestanden uit de mediabibliotheek</span>
    </div>
</div>
