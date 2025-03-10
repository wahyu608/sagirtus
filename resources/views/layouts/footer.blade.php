<footer class="bg-blue-800 dark:bg-gray-800 text-white py-6 mt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Brand -->
            <div class="text-lg font-semibold">
                SIGARTUS
            </div>
            
            <!-- Navigation Links -->
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="https://wa.me/6285739538196" class="hover:underline">Contact Us</a>
            
                @auth
                    <a href="{{ route('admin.about') }}" class="hover:underline">About</a>
                @endauth
            
                @guest
                    <a href="{{ route('guest.about') }}" class="hover:underline">About</a>
                @endguest
            </div>
            
            
            <!-- Copyright -->
            <div class="mt-4 md:mt-0 text-sm text-gray-300">
                &copy; {{ date('Y') }} SIGARTUS. All rights reserved.
            </div>
        </div>
    </div>
  </footer>
  