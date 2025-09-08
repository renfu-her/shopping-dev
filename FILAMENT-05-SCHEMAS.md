# Filament v4 Complete Guide - Part 5: Schema System

## Schema System

### Overview
**New Concept in v4:**
- Unified page structure definition for forms, infolists, and page layouts
- Supports multiple layout types, providing flexible page organization
- Highly customizable, supports completely custom components
- Based on Livewire 3 and Alpine.js, provides responsive interactive experience

## Layout Components

### Grid System
**Responsive grid layout:**
- Use `columns()` method to set column count
- Supports responsive breakpoints: `sm`, `md`, `lg`, `xl`, `2xl`
- Supports container queries: `@sm`, `@md`, `@lg`, `@xl`, `@2xl`
- Supports column span, column start position, and column order

**Example Code:**
```php
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;

Grid::make()
    ->columns([
        'default' => 1,
        'md' => 2,
        'xl' => 3,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                'default' => 1,
                'md' => 2,
            ]),
        TextInput::make('email')
            ->columnSpan([
                'default' => 1,
                'md' => 1,
            ]),
    ]);
```

### Container Queries
**Responsive layout based on container size:**
- Use `gridContainer()` method to mark container
- Supports breakpoints like `@md` (448px+), `@lg` (512px+), `@xl` (576px+)
- Provides fallback breakpoints with `!@` prefix for older browsers

**Example Code:**
```php
Grid::make()
    ->gridContainer()
    ->columns([
        '@md' => 3,
        '@xl' => 4,
        '!@md' => 2,  // Fallback breakpoint
        '!@xl' => 3,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
            ])
            ->columnOrder([
                'default' => 2,
                '@xl' => 1,
            ]),
    ]);
```

## Basic Layout Components

### Grid Component
**Grid layout supporting multi-column responsive design:**
- Supports responsive breakpoints and container queries
- Customizable column span, start position, and order
- Supports nested grid structures

**Example Code:**
```php
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;

Grid::make()
    ->columns([
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
    ])
    ->schema([
        TextInput::make('first_name')
            ->columnSpan([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ]),
        TextInput::make('last_name')
            ->columnSpan([
                'default' => 1,
                'md' => 1,
                'xl' => 3,
            ]),
        TextInput::make('email')
            ->columnSpan([
                'default' => 1,
                'md' => 3,
                'xl' => 6,
            ]),
    ]);
```

### Flex Component
**Flexible layout supporting flexbox properties:**
- Supports flex direction, alignment, and wrapping
- Customizable flex properties
- Suitable for complex layout requirements

**Example Code:**
```php
use Filament\Schemas\Components\Flex;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Button;

Flex::make()
    ->direction('row')
    ->justifyContent('space-between')
    ->alignItems('center')
    ->schema([
        TextInput::make('search')
            ->placeholder('Search...')
            ->flex(1),
        Button::make('Search')
            ->type('submit'),
    ]);
```

### Fieldset Component
**Fieldset for grouping related fields:**
- Provides visual grouping and borders
- Supports title and description
- Customizable column count and styles

**Example Code:**
```php
use Filament\Schemas\Components\Fieldset;

Fieldset::make('Personal Information')
    ->label('Personal Information')
    ->description('Enter your personal details below.')
    ->schema([
        TextInput::make('first_name'),
        TextInput::make('last_name'),
        TextInput::make('email'),
    ])
    ->columns(3)
    ->collapsible()
    ->collapsed();
```

**Remove Fieldset Border:**
```php
Fieldset::make('Personal Information')
    ->schema([
        TextInput::make('first_name'),
        TextInput::make('last_name'),
    ])
    ->columns(2)
    ->border(false); // Remove border
```

### Section Component
**Section providing content grouping with title and description:**
- Supports title, description, and icon
- Collapsible/expandable
- Supports custom styles

**Example Code:**
```php
use Filament\Schemas\Components\Section;

Section::make('Personal Information')
    ->description('Enter your personal details below.')
    ->icon('heroicon-o-user')
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
        TextInput::make('phone'),
    ])
    ->columns(3)
    ->collapsible()
    ->collapsed();
```

## Grid Column Control

### Column Span
**Control the number of columns a component spans:**
```php
TextInput::make('name')
    ->columnSpan([
        'default' => 1,  // Default device
        'sm' => 2,       // Small device
        'md' => 3,       // Medium device
        'lg' => 4,       // Large device
        'xl' => 6,       // Extra large device
    ]);
```

### Column Start
**Control the starting column position of a component:**
```php
TextInput::make('name')
    ->columnStart([
        'default' => 1,
        'md' => 2,  // Start from column 2
        'xl' => 3,  // Start from column 3
    ]);
```

### Column Order
**Control the display order of components:**
```php
TextInput::make('name')
    ->columnOrder([
        'default' => 2,  // Display second by default
        'md' => 1,       // Display first on medium devices
        'xl' => 3,       // Display third on extra large devices
    ]);

TextInput::make('email')
    ->columnOrder([
        'default' => 1,  // Display first by default
        'md' => 2,       // Display second on medium devices
        'xl' => 1,       // Display first on extra large devices
    ]);
```

## Responsive Grid Layout Example
**Complete responsive layout example:**
```php
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

Grid::make()
    ->columns([
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
    ])
    ->schema([
        // First name field - spans 2 columns on small devices, 2 columns on large devices
        TextInput::make('first_name')
            ->label('First Name')
            ->columnSpan([
                'default' => 1,
                'sm' => 2,
                'lg' => 2,
            ]),
        
        // Last name field - spans 2 columns on small devices, 2 columns on large devices
        TextInput::make('last_name')
            ->label('Last Name')
            ->columnSpan([
                'default' => 1,
                'sm' => 2,
                'lg' => 2,
            ]),
        
        // Email - spans full width on all devices
        TextInput::make('email')
            ->label('Email Address')
            ->columnSpan([
                'default' => 1,
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ]),
        
        // Phone number - spans 2 columns on medium devices
        TextInput::make('phone')
            ->label('Phone Number')
            ->columnSpan([
                'default' => 1,
                'md' => 2,
            ]),
        
        // Country selection - spans 1 column on medium devices
        Select::make('country')
            ->label('Country')
            ->options([
                'us' => 'United States',
                'ca' => 'Canada',
                'uk' => 'United Kingdom',
            ])
            ->columnSpan([
                'default' => 1,
                'md' => 1,
            ]),
        
        // Address - spans full width on all devices
        Textarea::make('address')
            ->label('Address')
            ->columnSpan([
                'default' => 1,
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ]),
    ]);
```

## Container Queries

### Basic Container Queries
```php
Grid::make()
    ->gridContainer()  // Mark as container
    ->columns([
        '@sm' => 2,   // Container width ≥ 384px
        '@md' => 3,   // Container width ≥ 448px
        '@lg' => 4,   // Container width ≥ 512px
        '@xl' => 6,   // Container width ≥ 576px
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
            ]),
        TextInput::make('email')
            ->columnSpan([
                '@md' => 1,
                '@xl' => 3,
            ]),
    ]);
```

### Container Queries with Column Ordering
```php
Grid::make()
    ->gridContainer()
    ->columns([
        '@md' => 3,
        '@xl' => 4,
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
            ])
            ->columnOrder([
                'default' => 2,  // Default second
                '@xl' => 1,      // First in extra large container
            ]),
        TextInput::make('email')
            ->columnSpan([
                'default' => 1,
                '@xl' => 1,
            ])
            ->columnOrder([
                'default' => 1,  // Default first
                '@xl' => 2,      // Second in extra large container
            ]),
    ]);
```

### Fallback Breakpoints for Older Browsers
```php
Grid::make()
    ->gridContainer()
    ->columns([
        '@md' => 3,    // Container query
        '@xl' => 4,    // Container query
        '!@md' => 2,   // Fallback breakpoint
        '!@xl' => 3,   // Fallback breakpoint
    ])
    ->schema([
        TextInput::make('name')
            ->columnSpan([
                '@md' => 2,
                '@xl' => 3,
                '!@md' => 2,
                '!@xl' => 2,
            ])
            ->columnOrder([
                'default' => 2,
                '@xl' => 1,
                '!@xl' => 1,
            ]),
        TextInput::make('email')
            ->columnOrder([
                'default' => 1,
                '@xl' => 2,
                '!@xl' => 2,
            ]),
    ]);
```

## Adding Extra HTML Attributes

### Static Attributes
```php
Section::make('Personal Information')
    ->extraAttributes([
        'class' => 'custom-section-style',
        'data-testid' => 'personal-info-section',
    ])
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

### Dynamic Attributes
```php
Section::make('Dynamic Section')
    ->extraAttributes(function ($get, $operation, $record) {
        return [
            'class' => $operation === 'create' ? 'create-mode' : 'edit-mode',
            'data-user-id' => $record?->id,
            'data-operation' => $operation,
        ];
    })
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

### Merging Attributes
```php
Section::make('Merged Section')
    ->extraAttributes(['class' => 'base-style'])
    ->extraAttributes(['class' => 'additional-style'], merge: true)
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

## Layout Best Practices

### Responsive Design Principles
- **Mobile First**: Start designing from the smallest screen
- **Progressive Enhancement**: Add more columns for larger screens
- **Consistency**: Maintain visual consistency across devices
- **Readability**: Ensure text remains readable on small screens

### Performance Optimization
- **Avoid Over-nesting**: Limit grid nesting depth
- **Reasonable Column Usage**: Avoid using too many columns on small screens
- **Conditional Rendering**: Use conditional display to reduce unnecessary components

### Accessibility
- **Keyboard Navigation**: Ensure all components are accessible via keyboard
- **Screen Readers**: Provide appropriate ARIA labels
- **Color Contrast**: Ensure sufficient color contrast

### Code Organization
- **Logical Grouping**: Organize related fields together
- **Naming Conventions**: Use consistent naming conventions
- **Comments**: Add comments for complex layouts

## Section Components
**Content grouping and organization:**
- Provides title, description, and icon
- Supports collapse/expand functionality
- Customizable styles and behavior
- Supports conditional display

**Example Code:**
```php
use Filament\Schemas\Components\Section;

Section::make('Personal Information')
    ->description('Enter your personal details below.')
    ->icon('heroicon-o-user')
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
        TextInput::make('phone'),
    ])
    ->collapsible()
    ->collapsed();
```

## Tab Components
**Tab-based navigation and content organization:**
- Supports horizontal, vertical, and icon tabs
- Customizable tab styles and behavior
- Supports conditional tab display
- Supports dynamic loading of tab content

**Example Code:**
```php
use Filament\Schemas\Components\Tabs;

Tabs::make('User Information')
    ->tabs([
        Tabs\Tab::make('Personal')
            ->icon('heroicon-o-user')
            ->schema([
                TextInput::make('name'),
                TextInput::make('email'),
            ]),
        Tabs\Tab::make('Address')
            ->icon('heroicon-o-map-pin')
            ->schema([
                TextInput::make('street'),
                TextInput::make('city'),
                TextInput::make('postal_code'),
            ]),
        Tabs\Tab::make('Settings')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Toggle::make('notifications'),
                Toggle::make('newsletter'),
            ]),
    ]);
```

## Wizard Components
**Step-by-step forms and workflows:**
- Supports multi-step forms
- Customizable step validation
- Supports data persistence between steps
- Provides progress indicators

**Example Code:**
```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    Wizard\Step::make('Personal Information')
        ->schema([
            TextInput::make('name'),
            TextInput::make('email'),
        ])
        ->validation([
            'name' => 'required|min:2',
            'email' => 'required|email',
        ]),
    Wizard\Step::make('Address')
        ->schema([
            TextInput::make('street'),
            TextInput::make('city'),
        ]),
    Wizard\Step::make('Review')
        ->schema([
            // Read-only field display
        ]),
]);
```

## Prime Components
**Basic UI components:**
- **Text**: Text display component
- **Image**: Image display component
- **Icon**: Icon display component
- **Badge**: Badge component
- **Button**: Button component
- **Link**: Link component

**Example Code:**
```php
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Badge;

Text::make('Welcome to our platform!')
    ->size('lg')
    ->weight('bold');

Image::make('logo')
    ->src('/images/logo.png')
    ->alt('Company Logo');

Badge::make('status')
    ->color('success')
    ->label('Active');
```

## Custom Components
**Fully customizable components:**
- Extends `Filament\Schemas\Components\Component`
- Supports custom rendering logic
- Can inject various utilities
- Supports responsive design

**Example Code:**
```php
use Filament\Schemas\Components\Component;

class CustomComponent extends Component
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function render(): View
    {
        return view('components.custom-component');
    }
}
```

## Utility Injection
**Injectable utilities:**
- **$component**: Current component instance
- **$get**: Function to get schema data
- **$livewire**: Livewire component instance
- **$model**: Eloquent model FQN
- **$operation**: Current operation (create, edit, view)
- **$record**: Eloquent record instance

**Example Code:**
```php
Section::make('Dynamic Content')
    ->schema([
        TextInput::make('title')
            ->visible(fn ($get, $operation, $record) => 
                $operation === 'create' || $record?->status === 'draft'
            ),
    ]);
```

## Best Practices

### Performance Optimization
- Use appropriate data loading strategies
- Avoid N+1 query problems
- Use conditional display reasonably

### User Experience
- Provide clear labels and descriptions
- Use appropriate colors and icons
- Ensure responsive design

### Accessibility
- Provide appropriate ARIA labels
- Ensure keyboard navigation support
- Use sufficient color contrast

---

**Previous:** [Part 4: Infolists](FILAMENT-04-INFOLISTS.md)  
**Next:** [Part 6: Actions & Navigation](FILAMENT-06-ACTIONS-NAVIGATION.md)
