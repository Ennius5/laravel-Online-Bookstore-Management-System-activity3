<nav class="bg-indigo-600 text-white shadow-lg">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
<div class="flex items-center">
<!-- Logo -->

<a href="{{ route('home') }}" class="text-xl font-
bold">

PageTurner
</a>
<!-- Navigation Links -->
<div class="hidden md:flex ml-10 space-x-4">

<a href="{{ route('home') }}" class="hover:bg-
indigo-700 px-3 py-2 rounded-md">

Home
</a>
<a href="{{ route('books.index') }}"
class="hover:bg-indigo-700 px-3 py-2 rounded-md">

Books
</a>

@auth
@if(auth()->user()->isAdmin())
<a

href="{{ route('admin.books.create') }}" class="hover:bg-indigo-700
px-3 py-2 rounded-md">

Add Book
</a>
<a

href="{{ route('admin.categories.index') }}" class="hover:bg-
indigo-700 px-3 py-2 rounded-md">

Categories Dashboard
</a>
@endif
@endauth
</div>
</div>
<!-- Right Side -->
<div class="flex items-center space-x-4">
@guest

<a href="{{ route('login') }}" class="hover:bg-
indigo-700 px-3 py-2 rounded-md">

Login
</a>
<a href="{{ route('register') }}" class="bg-white

text-indigo-600 px-4 py-2 rounded-md font-medium">

Register
</a>
@endguest
@auth
<a href="{{ route('orders.index') }}"

class="hover:bg-indigo-700 px-3 py-2 rounded-md">

My Orders
</a>
<a href="{{ route('dashboard') }}" class="hover:bg-
indigo-700 px-3 py-2 rounded-md">
<span class="text-indigo-200"> {{ auth()->user()->name }}</span>
</a>

<form method="POST" action="{{ route('logout') }}"

class="inline">

@csrf

<button type="submit" class="hover:bg-
indigo-700 px-3 py-2 rounded-md">

Logout
</button>
</form>
@endauth
</div>
</div>
</div>
</nav>
