<nav class="bg-amber-600 text-white shadow-xl shadow-amber-500/50">
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

<a href="{{ route('home') }}" class="hover:bg-amber-700 px-3 py-2 rounded-md">

Home
</a>
<a href="{{ route('books.index') }}"
class="hover:bg-amber-700 px-3 py-2 rounded-md">

Books
</a>

@auth
@if(auth()->user()->isAdmin())
<a

href="{{ route('admin.books.create') }}" class="hover:bg-amber-700 px-3 py-2 rounded-md">

Add Book
</a>
<a

href="{{ route('admin.categories.index') }}" class="hover:bg-amber-700 px-3 py-2 rounded-md">

Categories Dashboard
</a>
@endif
@endauth
</div>
</div>
<!-- Right Side -->
<div class="flex items-center space-x-4">
@guest

<a href="{{ route('login') }}" class="hover:bg-amber-700 px-3 py-2 rounded-md">

Login
</a>
<a href="{{ route('register') }}" class="hover:bg-amber-700 px-3 py-2 rounded-md">

Register
</a>
@endguest
@auth
<a href="{{ route('orders.index') }}"

class="hover:bg-amber-700 px-3 py-2 rounded-md">
@if(auth()->user()->isAdmin())
Manage orders
@else
My Orders
@endif
</a>
<a href="{{ route('dashboard') }}" class="hover:bg-
amber-700 px-3 py-2 rounded-md">
<span class="text-amber-200"> {{ auth()->user()->name }}</span>
</a>

<form method="POST" action="{{ route('logout') }}"

class="inline">

@csrf

<button type="submit" class="hover:bg-
amber-700 px-3 py-2 rounded-md">

Logout
</button>
</form>
@endauth
</div>
</div>
</div>
</nav>
