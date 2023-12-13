# SherlockodeCrud Bundle

----

## Overview

----
This bundle generate basic crud.

----

## Installation

----
Install the bundle with composer:

```bash
$ composer require sherlockode/crud-bundle
```

Generate a basic grid view:

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            config:
                class: App\Entity\User
                form: App\Form\Type\UserType
            grid:
                fields:
                    name:
                        label: crud.user.name #translation key
                    surname:
                        label: crud.user.surname
                    is_active:
                        label: crud.user.active
                        type: boolean # you can define a specific type if you need. See the section bellow 
                    created_at:
                        label: crud.user.created_at
                        type: date
                        options:
                            format: d-m-Y # you can customise the date format
                actions: 
                    update: ~
                    delete: ~
                settings: 
                    page_size: 20 #the number of elements by page, 20 by default
```

```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user 
    type: sherlockode_crud.resource
    prefix: /admin
```

---
## Customisation 

#### You need custom action or custom field? You can easily define your own

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    templates:
        action:
            up_and_down: '@SherlockodeCrud/common/grid/action/up_and_down.html.twig'
        field:
            custom_field: '@SherlockodeCrud/common/grid/field/custom_field.html.twig'

    # now, you can use them like this :
    crud: 
        user:
            # ...
            grid:
                fields:
                    surname:
                        label: crud.user.email
                        type: custom_field
                actions:
                    up_and_down: ~
```

#### You need the object instead of a property value in the grid?  

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud: 
        user:
            # ...
            grid:
                fields:
                    surname:
                        label: crud.user.email
                        path: . #use '.' to send the object instead of the property value
```

#### You need to add filters on your grid?
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                filters:
                    name:
                        type: string
                        label: Label or custom translation key
```

#### You need to create a custom query for the grid?
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                repository:
                    method: yourQueryBuilder
```

- In this example, in the UserRepository, you need to have a function named `yourQueryBuilder`
- The `yourQueryBuilder` function need to return a `QueryBuilder` object

#### You need filters?
The bundle has basic filters
- String
- Boolean
- Float
- Money
- Date
- DateRange
- Entity

Entity filter need more configuration:

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                filters:
                    category:
                        type: entity
                        options:
                            class: App\Entity\Category
                            choice_label: name
```

To add some filters: 
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                filters:
                    name:
                        type: string #the filter name
                    createdAt:
                        type: date_range
```

#### You need custom filter?
If you need a filter that does not exist, create it !

Create your own filter class and your own filter type class, in this example `MyCustomFilter`.
`MyCustomFilter` need to implements `FilterInterface`

Now you need to set the template for your new filter:
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    templates:
        filter:
            my_custom_filter: 'Grid\Filter\my_custom_filter.html.twig'
    crud:
        user:
            # ...
            grid:
                filters:
                    name:
                        type: my_custom_filter
```

#### You need to sort your grid?
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                sorting:
                    name: asc
                    surname: desc
```


#### You need to let the user choose the order?
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            grid:
                fields:
                    name:
                        label: crud.user.email
                        sortable: ~ #set the column sortable
```

#### You need to change the redirection a resource creation or edition?

```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        redirect_after_create: index 
        redirect_after_update: update 
        base_name: sherlockode_crud
        resource_name: user 
    type: sherlockode_crud.resource
    prefix: /admin
```
By default, after a resource creation or edition, you will be redirected to the update action

#### You need a custom template for a route?

```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        templates: "User"
    type: sherlockode_crud.resource
    prefix: /admin
```
- In your templates project folder, be sure you have the `User` directory. 
- If you need a custom template only for the index action, name it `index.html.twig`, other routes will be rendered with the defaults templates 


#### You only want to create the index route, not all of them?
```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        only: [index]
    type: sherlockode_crud.resource
    prefix: /admin
```

#### You want to create all routes excepted delete?
```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        except: [delete]
    type: sherlockode_crud.resource
    prefix: /admin
```

#### You need to check permission before action?
```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        permission: true
    type: sherlockode_crud.resource
    prefix: /admin
```

Now make your own voter for each action
- index
- create
- edit
- delete

The attribute is prefixed by the `resource_name`. In this example, it's `user_index` 

#### You want to send some variable?
```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        vars: 
            global:
                icon: bi bi-person-fill
```

In this example, we send an icon to all paths.

If you want to do this only for a specific path:

```yaml
# sherlockode_crud_routing.yaml

app_admin_user:
    resource: |
        base_name: sherlockode_crud
        resource_name: user
        vars: 
            index:
                icon: bi bi-person-fill
```

#### You need to remove the delete confirmation page?
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            config:
                delete_confirmation: false
```

You need to add some information in the show view?

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            show:
                name:
                    type: string
                is_active:
                    type: boolean
                category.name:
                    type: string
                created_at:
                    type: date
                    options:
                        format: d-m-Y
```

You need to change the translation domain?

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    translation_domain: yourDomain
```


You need to disable the translation domain?

```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    translation_domain: false
    crud:
        user:
            # ...
            grid:
                fields:
                    name:
                        label: My Name #you can use a translation key or the label value if translation domain is false
```

If you need to be more specific, you can disable translation or set a custom for some grids
```yaml
# config/packages/sherlockode_crud.yaml

sherlockode_crud:
    crud:
        user:
            config:
                translation_domain: false #or yourDomain
```
If you set a translation_domain for a grid, the value will replace the global one

#### You need to send data to the views?
In ResourceControllerDataEvent, you have several actions : 
- ResourceControllerDataEvent::SHOW
- ResourceControllerDataEvent::CREATE
- ResourceControllerDataEvent::UPDATE
- ResourceControllerDataEvent::DELETE_CONFIRMATION

In ResourceController, in show, create, update and delete confirmation actions, an even is dispatched before the page is rendered.

If you need to send data to the view, you can create a listener.
```php
# src/EventListener/ResourceListener.php

class ResourceListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceControllerDataEvent::UPDATE => 'update',
        ];
    }

    public function update(ResourceControllerDataEvent $event): void
    {
        // send custom data to the view
        $event->setData([]);
    }
}
```

In the view, the data variable will contain your data sent in the example above.

#### You need to prevent flush?
In ResourceControllerEvent, you have several actions :
- ResourceControllerEvent::BEFORE_CREATE
- ResourceControllerEvent::BEFORE_UPDATE
- ResourceControllerEvent::BEFORE_DELETE

In ResourceController, in create, update and delete actions, an even is dispatched before flush is performed.

If you need to cancel the flush, you can create a listener
```php
# src/EventListener/ResourceListener.php

class ResourceListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceControllerEvent::BEFORE_UPDATE => 'update',
        ];
    }

    public function update(ResourceControllerDataEvent $event): void
    {
        $event->setCancelProcess(true);
        
        // optional message, by default, a translation key is generated 
        // sherlockode_crud.crud_name.update.cancel
        $event->setMessage('your message');
    }
}
```
