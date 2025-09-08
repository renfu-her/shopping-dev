# Filament v4 Complete Guide - Part 1: Overview & Architecture

## Overview

Filament v4 is a major version update of the Filament framework, bringing many new features and architectural improvements. This document will detail the new features of v4, differences from v3, and how to use these new features.

## Major Changes

### 1. Architectural Refactoring

#### 1.1 Panel System
- **v3**: Single panel concept
- **v4**: Multi-panel support, can create multiple independent admin panels
- Each panel can have its own users, resources, pages, and configurations
- Support for different domains and subdomains

#### 1.2 Component Architecture
- **v3**: Based on Blade components
- **v4**: Fully based on Livewire 3 and Alpine.js
- Better responsive design and interactive experience
- More powerful real-time update capabilities

### 2. Resource System

#### 2.1 Resource Overview
Filament resources are complete CRUD interfaces for managing Eloquent models. Each resource contains the following pages:
- **List** - Paginated table displaying all records
- **Create** - Form for creating new records
- **Edit** - Form for editing existing records
- **View** - Read-only record display (new in v4)

#### 2.2 Resource Pages Details

##### 2.2.1 Listing Records
**New Features:**
- **Tab Filtering**: Use `getTabs()` method to add tabs for filtering records
- **Custom Tab Labels**: Use `Tab::make('Custom Label')` to customize tab names
- **Tab Icons**: Use `icon()` method to add icons, supports `iconPosition()` to set position
- **Tab Badges**: Use `badge()` method to add badges, supports `badgeColor()` to set color
- **Default Tab**: Use `getDefaultActiveTab()` to set the default selected tab
- **Custom Queries**: Use `modifyQueryUsing()` to customize Eloquent queries
- **Custom Page Content**: Use `content()` method to customize page structure

**Example Code:**
```php
public function getTabs(): array
{
    return [
        'all' => Tab::make('All customers'),
        'active' => Tab::make('Active customers')
            ->icon('heroicon-m-check-circle')
            ->badge(Customer::where('active', true)->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
        'inactive' => Tab::make('Inactive customers')
            ->icon('heroicon-m-x-circle')
            ->badge(Customer::where('active', false)->count())
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
    ];
}

public function getDefaultActiveTab(): string | int | null
{
    return 'active';
}
```

##### 2.2.2 Creating Records
**New Features:**
- **Pre-save Data Customization**: Use `mutateFormDataBeforeCreate()` method
- **Custom Creation Process**: Use `create()` method to completely customize creation logic
- **Custom Redirect**: Use `getRedirectUrl()` method
- **Custom Notifications**: Use `getCreatedNotification()` method
- **Create Another Record**: Support for "create another" functionality, can be disabled via `hasCreateAnother()`
- **Lifecycle Hooks**: Provides multiple hook methods like `beforeCreate()`, `afterCreate()`
- **Wizard Support**: Use `wizard()` method to enable step-by-step forms
- **Import Functionality**: Support for bulk record import

**Example Code:**
```php
protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['user_id'] = auth()->id();
    return $data;
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

protected function getCreatedNotification(): ?Notification
{
    return Notification::make()
        ->success()
        ->title('User registered')
        ->body('The user has been created successfully.');
}
```

##### 2.2.3 Editing Records
**New Features:**
- **Pre-save Data Customization**: Use `mutateFormDataBeforeSave()` method
- **Custom Save Process**: Use `save()` method to completely customize save logic
- **Custom Redirect**: Use `getRedirectUrl()` method
- **Custom Notifications**: Use `getSavedNotification()` method
- **Lifecycle Hooks**: Provides multiple hook methods like `beforeSave()`, `afterSave()`
- **Wizard Support**: Use `wizard()` method to enable step-by-step forms

##### 2.2.4 Viewing Records
**Brand New Feature in v4:**
- Read-only record display page
- Uses Infolist components to display data
- Supports custom layouts and styles
- Can add custom action buttons

##### 2.2.5 Deleting Records
**New Features:**
- **Soft Delete Support**: Automatically handles soft delete models
- **Force Delete**: Supports permanent record deletion
- **Bulk Delete**: Supports bulk delete operations
- **Custom Delete Logic**: Use `delete()` method to customize delete process

#### 2.3 Managing Relationships
**New Features:**
- **BelongsTo Relationships**: Supports dropdown selection and search
- **HasMany Relationships**: Supports inline table management
- **ManyToMany Relationships**: Supports tag input and multi-select
- **Custom Relationship Fields**: Complete customization of relationship display and editing

#### 2.4 Nested Resources
**New Feature in v4:**
- Supports parent-child relationship resource management
- Automatically handles URL structure
- Supports multi-level nesting
- Custom nesting logic

#### 2.5 Singular Resources
**New Feature in v4:**
- Resources for managing single records
- Suitable for settings, configuration scenarios
- Automatically hides list page

#### 2.6 Global Search
**New Feature in v4:**
- Unified search functionality across resources
- Supports custom search logic
- Supports search result highlighting
- Configurable search scope

## Installation and Configuration

### Basic Installation
```bash
composer require filament/filament:"^4.0"
php artisan filament:install --panels
```

### Create Panel
```bash
php artisan make:filament-panel admin
```

### Create Resource
```bash
php artisan make:filament-resource User
```

### Create Widget
```bash
php artisan make:filament-widget StatsOverview
```

## Best Practices

### 1. Code Organization
- Use appropriate namespaces
- Follow PSR standards
- Keep code clean

### 2. Performance Optimization
- Use appropriate database queries
- Implement caching strategies
- Optimize resource loading

### 3. User Experience
- Provide clear navigation
- Use consistent design language
- Implement responsive design

### 4. Security
- Implement appropriate permission controls
- Validate all user input
- Protect sensitive data

## Reference Resources

- [Official Documentation](https://filamentphp.com/docs/4.x)
- [GitHub Repository](https://github.com/filamentphp/filament)
- [Community Forum](https://filamentphp.com/community)
- [Plugin Marketplace](https://filamentphp.com/plugins)

---

**Next Parts:**
- [Part 2: Table System](FILAMENT-02-TABLES.md)
- [Part 3: Form System](FILAMENT-03-FORMS.md)
- [Part 4: Infolists](FILAMENT-04-INFOLISTS.md)
- [Part 5: Schema System](FILAMENT-05-SCHEMAS.md)
- [Part 6: Actions & Navigation](FILAMENT-06-ACTIONS-NAVIGATION.md)
