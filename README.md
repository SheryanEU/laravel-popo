# Introduction to Popo
"Popo" stands for "Plain Old PHP Object," a concept inspired by the "Plain Old Java Object" (Pojo) paradigm.
The primary aim of introducing Popo is to transition away from using key-value arrays for variable management in favor of utilizing strictly typed PHP objects.
This approach enhances code clarity, type safety, and object-oriented programming principles within PHP projects.

The package comprises a solitary class, `BasePopo`, designed to serve as a foundational element that all other Popo instances can extend.
This architectural design enables any Popo derived from `BasePopo` to be effortlessly returned from controller functions while maintaining the capability to be converted into an array.
This feature ensures that our objects remain flexible and compatible with a wide range of PHP functionalities, fostering both ease of use and integration.

## Usage
When creating a new Popo object simply extend BasePopo. Only public attributes will be included when converting to an array or
when returning the Popo from a controller function, it is therefor required you mark visible attributes as public.

```php
use SheryanEu\LaravelPopo\HasPopoFactory;

class ProductPopo extends BasePopo
{
    use HasPopoFactory;
    
    /**
     * @var string
     */
    public string $name;

    /**
     * @var float
     */
    public float $price;

    /**
     * @param string $name
     * @param float  $price
     */
    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
    
    /**
     * @return string
     */
    public static function popoFactory(): string
    {
        return ProductPopoFactory::class;
    }
}
```

This Popo can now be instantiated. by using the fromObject function like so: `ProductPopo::fromObject($data);`. You can also instantiate an array of this Popo from
an array of objects using the fromArray function like so: `ProductPopo::fromArray($array);`.  

## Testing

When writing tests for your application, you may find it useful to create instances of your Popo objects. One way to do this is by using factories.

A factory is a class that is responsible for creating instances of another class. In the context of Popo objects, a factory can be used to create instances of your Popo objects with random or specific values, which can be very useful for testing.

### Testing usage

First, create a factory class for your Popo object. The factory class should have a method that returns an instance of your Popo object with random or specific values. Here's an example factory class for the ProductPopo object.

```php
use Tests\Popo\ProductPopo;
use SheryanEu\LaravelPopo\PopoFactory;

class ProductPopoFactory extends PopoFactory
{
    /**
     * @var null|string
     */
    public ?string $popoClass = ProductPopo::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomNumber(1, 10),
        ];
    }
}
```

### Factory create

Let's say you have a service function `create` that takes in a ProductPopo object and creates a new product based on its properties. You can use Popo factories to easily create test data for this function:

```php
/** @test */
public function can_create_product(): void
{
    $productPopo = ProductPopo::factory()->create();

    $product = $this->productService->create($productPopo);

    $this->assertDatabaseHas('products', [
        'name' => $productPopo->name,
        'price' => $productPopo->price,
    ]);
}
```

### Create multiple

Sometimes you may want to create multiple instances of a Popo with slightly different attributes in your unit tests. You can achieve this using the `count()` and `sequence()` methods provided by the package.

The `count(int $count)` method takes an integer as its argument and tells the factory how many instances to create. The `sequence(array|closure $sequense)` method takes an array of attribute values for each instance to create. You can also provide a closure to the `sequence()` method that returns an array of attribute values.

Here's an example of how you can use `count()` and `sequence()` to create multiple instances of a Popo with slightly different attributes:

```php
ProductPopo::factory()
    ->count(3)
    ->sequence(
        ['name' => 'Apples'],
        ['name' => 'Cookies'],
        ['name' => 'Bread'],
    )
    ->create();
```

### Raw data

The `raw(array $attributes = [])` function returns an array with the attributes that will be used to create an array with the values from the factory.

```php
$productData = ProductPopo::factory()->raw();
```

### Overriding data

The `state()` function is a powerful feature of the Laravel factories that allows you to define a set of attributes that will override the default values of the factory. This can be useful when you need to create a Popo with a specific set of attributes that cannot be generated by the default factory.

Here's an example of how you can use the `state()` function to set the state of a Popo:

```php
class ProductPopoFactory extends Factory
{
    ...

    public function published(): array
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => true,
            ];
        });
    }
}
```

In this example, we have defined a factory for the `ProductPopo` class with a default set of attributes that includes a `published` attribute set to false. We have also defined a `published()` function that uses the `state()` function to set the `published` attribute to true.

Now, let's say we want to create a Popo that is published. We can do this by calling the published() function on the factory:

```php
$popo = ProductPopo::factory()->published()->create();
```

You can override specific data by passing an array of attribute values to the `create()` function. The array should contain the attribute names as keys and the desired values as their corresponding values. The values in the array will override any default values and any values that have been set using the `state()` function.

```php
$popo = ProductPopo::factory()->create([
    'name' => 'New product',
]);
```

In the above example, we are creating a new ProductPopo instance and overriding the default name value with our own value.

Similarly, you can also override specific data using the `raw()` function. By passing an array of attribute values to the `raw()` function, the values will override any default values and any values that have been set using the `state()` function.

```php
$productData = ProductPopo::factory()->raw([
    'name' => 'New product',
]);
```

In the above example, we are creating a new array of ProductPopo instance attributes using the `raw()` function and overriding the default name value with our own value.

### Acknowledgements
This package draws inspiration from `Bas Bieling`'s insightful presentation on Plain Old PHP Objects (POPOs), delivered on January 18, 2024, during a session hosted by [Meetup.com](https://www.meetup.com/). We extend our gratitude for the knowledge shared, which significantly influenced the development of this project.

### Upcoming Enhancements
In the near future, this package will be updated to incorporate [constructor property]( https://stitcher.io/blog/constructor-promotion-in-php-8), aligning with the latest advancements in PHP 8. This enhancement is aimed at streamlining our codebase and improving overall efficiency and readability.
