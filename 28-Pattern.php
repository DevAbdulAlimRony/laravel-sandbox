<?php
/*
|--------------------------------------------------------------------------
| Design Patterns
|--------------------------------------------------------------------------
| 1. Recipes for Building maintainable codes
| 2. Provides a way to solve issues, makes communication between developers more efficient
| 3. Maintainable code: Understandable, Intutive, Adaptable, Extendable, Debuggable
| 4. DRY- Don't Repeat Yourself
| 5. KISS- Keep it Simple Stupid
| 6. Types: Creational, Structural, Behavioral
*/

/*
Some Patterns in Laravel
    -Factory Pattern (Ingredients)
    -Strategy Pattern (Pizza Transport)
    -Provider Pattern (Pizza Shop/Service)
    -Repository Pattern
    -Iterator Pattern
    -Singleton Pattern
    -Builder Pattern (Pizza)
    -Presenter Pattern
*/

//Try to Understand how all patterns work in laravel.

/*
Adapter Pattern   
*/

//Laravel Eloquent already a repository pattern, so it is bad practice to use repository pattern again. Its like over-engineering.

//Loose Coupling
/*Imagine you have an e-commerce website built with Laravel. In this website, you have a product catalog feature where users can view and purchase products. To implement this, you'll have two main components: the ProductController and the ShoppingCartController.

Loose Coupling Example:

    ProductController: This controller is responsible for displaying product information. It retrieves product data from a database and renders it in a view.

    ShoppingCartController: This controller manages the shopping cart. It allows users to add and remove items from their cart.

Now, let's look at how these controllers can be loosely coupled:

Loose Coupling Aspect 1 - Separation of Concerns:

    The ProductController focuses solely on product-related functionality (displaying product details).
    The ShoppingCartController focuses on cart-related functionality (managing items in the shopping cart).
    Each controller has a distinct and well-defined responsibility.

Loose Coupling Aspect 2 - Minimal Direct Dependencies:

    The ProductController doesn't directly depend on the ShoppingCartController, and vice versa.
    They don't call each other's methods or share variables directly.

Loose Coupling Aspect 3 - Dependency Injection:

    If the ShoppingCartController needs information about products (e.g., to add a product to the cart), it can inject a ProductService or ProductRepository interface.
    This allows the ShoppingCartController to interact with product-related functionality without being tightly coupled to the ProductController.

Loose Coupling Aspect 4 - Event-Based Communication:

    Instead of directly interacting with each other, these controllers can communicate through events and listeners in Laravel.
    For example, when a product is added to the cart in the ShoppingCartController, it can trigger an event (e.g., ProductAddedToCart).
    The ProductController can listen for this event and take any necessary actions (e.g., updating product availability). */



