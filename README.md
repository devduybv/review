- [Configuration](#configuration)
  - [Model and Transformer](#model-and-transformer)
  - [Auth middleware](#auth-middleware)
- [Routes](#routes)

## Configuration
### Model and Transformer
You can use your own model and transformer class by modifying the configuration file `config\review.php`

```php
'models'          => [
    'review' => App\Entities\Review::class,
],

'transformers'    => [
    'review' => App\Transformers\ReviewTransformer::class,
],
```
### Auth middleware
Configure auth middleware in configuration file `config\review.php`

```php
'auth_middleware' => [
        'admin'    => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
],
```
## Routes

The api endpoint should have these format:
| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | reviews/{resource_type}                    |
| GET    | reviews/{resource_type}/{id}               |
| POST   | reviews/{resource_type}                    |



