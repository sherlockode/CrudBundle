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
# sherlockode_crud_routing.yaml

sherlockode_crud:
    crud:
        user:
            # ...
            grid:
                filters:
                    name:
                        type: string
```

#### You need to create a custom query for the grid?
```yaml
# sherlockode_crud_routing.yaml

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
- Date
- DateRange

To add some filters: 
```yaml
# sherlockode_crud_routing.yaml

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
```yaml
# service.yaml

App\Grid\Filter\MyCustomFilter:
    tags:
        -
            name: sherlockode_crud.filter
            type: my_custom_filter
            form_type: App\Form\Type\Filter\MyCustomFilterType
```

`MyCustomFilter` need to implements `FilterInterface`

Now you need to set the template and you can use it: 
```yaml
# sherlockode_crud_routing.yaml

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
# sherlockode_crud_routing.yaml

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
# sherlockode_crud_routing.yaml

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
