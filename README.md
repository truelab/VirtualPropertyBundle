# Truelab Virtual Property Bundle

Create virtual properties for Doctrine ORM entities on postLoad and postFlush events.

This bundle can be useful when you have virtual properties of entities that are not persisted on database but that you want to generate on the fly. 

Examples: 

- Link with absolute url that you don't want to save on database
- Properties with avarage or other math operations 
- Labels or translations that you want to generate on the server
- ... 

## Installing

1. Install with composer

2. Enable the bundle

## Usage

- Create a Doctrine Entity 

- Add @VirtualProperty annotation on the property that is not persisted

```php
use Truelab\VirtualPropertyBundle\Annotation\VirtualProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @VirtualProperty()
     */
    protected $link;
}
```

- Create a service to generate the value of that property on the fly 

```php
use Truelab\TestBundle\Entity\Post;

class TestGenerator
{

    public function generateLink(Post $entity)
    {
        $entity->setLink("http://link-to-my-post-generated");

        return $entity;
    }
}
```

- Tag the service with these attributes: 

    - `name="truelab_virtual_property.virtual_property_generator"`
    
    - `class="The class with the virtual property"`
    
    - `method="The method to call"`
    
    - `property="The virtual property"`
    
Example: 

```xml
<service id="truelab_test.test_generator" class="Truelab\TestBundle\Generator\TestGenerator">
    <tag name="truelab_virtual_property.virtual_property_generator"
         class="Truelab\TestBundle\Entity\Post"
         method="generateLink"
         property="link" />
</service>
```




