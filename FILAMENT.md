# Filament v4 Complete Guide

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

### 3. Table System

#### 3.1 Column Types
**New Column Types in v4:**
- **Text Column**: Text display with formatting support
- **Icon Column**: Icon display
- **Image Column**: Image display
- **Color Column**: Color display
- **Select Column**: Dropdown selection
- **Toggle Column**: Toggle switch
- **Text Input Column**: Inline text editing
- **Checkbox Column**: Checkbox selection

**Example Code:**
```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

TextColumn::make('name')
    ->searchable()
    ->sortable()
    ->formatStateUsing(fn (string $state): string => ucfirst($state));

ImageColumn::make('avatar')
    ->circular()
    ->size(40);

ToggleColumn::make('is_active')
    ->onColor('success')
    ->offColor('danger');
```

#### 3.2 Filters
**Enhanced Features in v4:**
- **Select Filters**: Dropdown selection filters
- **Ternary Filters**: Ternary filters (Yes/No/All)
- **Query Builder**: Advanced query builder
- **Custom Filters**: Complete customization of filter logic
- **Filter Layout**: Supports different filter layouts

#### 3.3 Actions
**Enhanced Features in v4:**
- **Modal Actions**: Supports popup-style actions
- **Action Grouping**: Organizes related actions together
- **New Action Types**:
  - Replicate (Copy)
  - Force-delete (Force Delete)
  - Restore (Restore)
  - Import (Import)
  - Export (Export)

#### 3.4 Layout and Features
- **Row Grouping**: Supports grouping display by columns
- **Summaries**: Statistical information at table bottom
- **Custom Data**: Supports non-Eloquent data sources
- **Empty State**: Custom empty data display

### 4. Form System

#### 4.1 Overview
**v4 Form System Features:**
- Responsive forms based on Livewire 3
- Supports real-time validation and error handling
- Rich field types and validation options
- Highly customizable styles and behavior
- Supports conditional fields and dynamic forms

#### 4.2 Basic Field Components

##### 4.2.1 Text Input
**Features:**
- Supports multiple input types: text, email, password, number, tel, url
- Real-time validation and error display
- Supports prefix, suffix, and icons
- Autocomplete and suggestion functionality
- Supports masked input

**Example Code:**
```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->label('Full Name')
    ->required()
    ->maxLength(255)
    ->minLength(2)
    ->unique(ignoreRecord: true)
    ->placeholder('Enter your full name')
    ->prefix('👤')
    ->suffix('@example.com')
    ->helperText('Enter your first and last name')
    ->live(onBlur: true)
    ->afterStateUpdated(function ($state, $set) {
        $set('slug', Str::slug($state));
    });
```

##### 4.2.2 Select
**Features:**
- Supports option arrays and relationship queries
- Search and filter functionality
- Grouped options support
- Multi-select and single-select modes
- Custom option rendering

**Example Code:**
```php
use Filament\Forms\Components\Select;

Select::make('status')
    ->options([
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ])
    ->required()
    ->searchable()
    ->preload()
    ->native(false)
    ->placeholder('Select a status')
    ->default('draft');

// Relationship selector
Select::make('user_id')
    ->relationship('user', 'name')
    ->searchable()
    ->preload()
    ->createOptionForm([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
    ]);
```

##### 4.2.3 Checkbox
**Features:**
- Single checkbox and checkbox lists
- Custom labels and descriptions
- Conditional display and validation
- Supports boolean and array values

**Example Code:**
```php
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;

Checkbox::make('terms')
    ->label('I agree to the terms and conditions')
    ->required()
    ->accepted();

CheckboxList::make('permissions')
    ->options([
        'read' => 'Read',
        'write' => 'Write',
        'delete' => 'Delete',
        'admin' => 'Administrator',
    ])
    ->columns(2)
    ->required();
```

##### 4.2.4 Toggle
**Features:**
- Boolean toggle switch
- Custom toggle labels
- Supports inline and block-level display
- Custom colors and styles

**Example Code:**
```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_active')
    ->label('Active Status')
    ->onIcon('heroicon-s-check')
    ->offIcon('heroicon-s-x-mark')
    ->onColor('success')
    ->offColor('danger')
    ->inline(false)
    ->default(true);
```

##### 4.2.5 Radio
**Features:**
- Radio button groups
- Supports vertical and horizontal layouts
- Custom option rendering
- Conditional display and validation

**Example Code:**
```php
use Filament\Forms\Components\Radio;

Radio::make('notification_type')
    ->options([
        'email' => 'Email notifications',
        'sms' => 'SMS notifications',
        'push' => 'Push notifications',
        'none' => 'No notifications',
    ])
    ->columns(2)
    ->default('email');
```

##### 4.2.6 Date Time Picker
**Features:**
- Date, time, and datetime selection
- Custom formats and localization
- Range selection support
- Timezone handling

**Example Code:**
```php
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;

DateTimePicker::make('published_at')
    ->label('Publish Date & Time')
    ->displayFormat('M j, Y g:i A')
    ->native(false)
    ->seconds(false)
    ->timezone('UTC');

DatePicker::make('birth_date')
    ->label('Date of Birth')
    ->native(false)
    ->maxDate(now())
    ->displayFormat('M j, Y');
```

##### 4.2.7 File Upload
**Features:**
- Single and multiple file upload
- Supports drag and drop upload
- File type validation
- Image preview and cropping
- Cloud storage support

**Example Code:**
```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('avatar')
    ->label('Profile Picture')
    ->image()
    ->imageEditor()
    ->imageCropAspectRatio('1:1')
    ->imageResizeTargetWidth('192')
    ->imageResizeTargetHeight('192')
    ->directory('avatars')
    ->maxSize(5120)
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']);

FileUpload::make('documents')
    ->label('Documents')
    ->multiple()
    ->maxFiles(5)
    ->acceptedFileTypes(['application/pdf', 'application/msword'])
    ->maxSize(10240);
```

#### 4.3 Advanced Field Components

##### 4.3.1 Rich Editor
**Features:**
- TipTap-based rich text editor
- Customizable toolbar buttons
- Supports images, links, and tables
- Real-time collaboration support
- Custom content validation

**Example Code:**
```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->label('Article Content')
    ->toolbarButtons([
        'bold',
        'italic',
        'underline',
        'strike',
        'link',
        'bulletList',
        'orderedList',
        'h2',
        'h3',
        'blockquote',
        'codeBlock',
        'table',
    ])
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads')
    ->fileAttachmentsVisibility('public')
    ->columnSpanFull();
```

##### 4.3.2 Markdown Editor
**Features:**
- Supports Markdown syntax
- Real-time preview
- Syntax highlighting
- Custom toolbar
- File attachment support

**Example Code:**
```php
use Filament\Forms\Components\MarkdownEditor;

MarkdownEditor::make('description')
    ->label('Description')
    ->toolbarButtons([
        'bold',
        'italic',
        'link',
        'bulletList',
        'orderedList',
        'h2',
        'h3',
        'blockquote',
        'codeBlock',
    ])
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsDirectory('uploads');
```

##### 4.3.3 Repeater
**Features:**
- Dynamic add/remove field groups
- Supports nested repeaters
- Custom item labels
- Conditional display and validation

**Example Code:**
```php
use Filament\Forms\Components\Repeater;

Repeater::make('phone_numbers')
    ->schema([
        Select::make('type')
            ->options([
                'mobile' => 'Mobile',
                'home' => 'Home',
                'work' => 'Work',
            ])
            ->required(),
        TextInput::make('number')
            ->tel()
            ->required(),
    ])
    ->defaultItems(1)
    ->reorderableWithButtons()
    ->collapsible();
```

##### 4.3.4 Builder
**Features:**
- Dynamic content block building
- Drag and drop sorting
- Custom block types
- Conditional display and validation

**Example Code:**
```php
use Filament\Forms\Components\Builder;

Builder::make('content_blocks')
    ->blocks([
        Builder\Block::make('text')
            ->schema([
                TextInput::make('title')->required(),
                RichEditor::make('content')->required(),
            ]),
        Builder\Block::make('image')
            ->schema([
                TextInput::make('title')->required(),
                FileUpload::make('image')->image()->required(),
                TextInput::make('alt_text'),
            ]),
        Builder\Block::make('video')
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('video_url')->url()->required(),
            ]),
    ])
    ->collapsible();
```

##### 4.3.5 Tags Input
**Features:**
- Dynamic tag add/remove
- Autocomplete suggestions
- Custom separators
- Tag validation

**Example Code:**
```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->label('Article Tags')
    ->separator(',')
    ->suggestions([
        'laravel',
        'filament',
        'php',
        'javascript',
        'vue',
        'react',
    ])
    ->maxTags(10);
```

##### 4.3.6 Textarea
**Features:**
- Multi-line text input
- Auto-adjusting height
- Character count
- Custom row count

**Example Code:**
```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->label('Description')
    ->rows(5)
    ->cols(50)
    ->maxLength(1000)
    ->characterCount()
    ->placeholder('Enter a detailed description...');
```

##### 4.3.7 Key Value
**Features:**
- Dynamic key-value pair input
- Custom key and value validation
- Supports nested structures
- Bulk operations

**Example Code:**
```php
use Filament\Forms\Components\KeyValue;

KeyValue::make('metadata')
    ->label('Custom Metadata')
    ->keyLabel('Property')
    ->valueLabel('Value')
    ->keyPlaceholder('Enter property name')
    ->valuePlaceholder('Enter property value')
    ->addActionLabel('Add Property')
    ->columnSpanFull();
```

##### 4.3.8 Color Picker
**Features:**
- Color selection and preview
- Supports multiple color formats
- Preset color options
- Custom color palette

**Example Code:**
```php
use Filament\Forms\Components\ColorPicker;

ColorPicker::make('theme_color')
    ->label('Theme Color')
    ->default('#3B82F6')
    ->format('hex')
    ->presetColors([
        '#3B82F6', // Blue
        '#EF4444', // Red
        '#10B981', // Green
        '#F59E0B', // Yellow
        '#8B5CF6', // Purple
    ]);
```

##### 4.3.9 Toggle Buttons
**Features:**
- Button group style selector
- Supports single and multi-select
- Custom button styles
- Icon and label support

**Example Code:**
```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ])
    ->colors([
        'draft' => 'gray',
        'published' => 'success',
        'archived' => 'danger',
    ])
    ->icons([
        'draft' => 'heroicon-o-pencil',
        'published' => 'heroicon-o-check-circle',
        'archived' => 'heroicon-o-archive-box',
    ])
    ->inline();
```

##### 4.3.10 Slider
**Features:**
- Numeric range selection
- Custom step and range
- Real-time value display
- Custom labels

**Example Code:**
```php
use Filament\Forms\Components\Slider;

Slider::make('rating')
    ->label('Rating')
    ->minValue(1)
    ->maxValue(5)
    ->step(0.5)
    ->displaySteps(5)
    ->default(3);
```

##### 4.3.11 Code Editor
**Features:**
- Syntax highlighting support
- Multiple programming languages
- Autocomplete
- Line number display

**Example Code:**
```php
use Filament\Forms\Components\CodeEditor;

CodeEditor::make('custom_css')
    ->label('Custom CSS')
    ->language('css')
    ->minHeight(200)
    ->maxHeight(500);
```

##### 4.3.12 Hidden
**Features:**
- Hidden form fields
- Used for storing calculated values or default values
- Supports dynamic value setting

**Example Code:**
```php
use Filament\Forms\Components\Hidden;

Hidden::make('user_id')
    ->default(fn () => auth()->id());

Hidden::make('created_at')
    ->default(now());
```

#### 4.4 Validation System
**Features:**
- Supports all Laravel validation rules
- Real-time validation and error display
- Custom validation messages
- Conditional validation
- Cross-field validation

**Example Code:**
```php
TextInput::make('email')
    ->email()
    ->required()
    ->unique(table: User::class, column: 'email', ignoreRecord: true)
    ->rules([
        'email',
        'max:255',
        Rule::unique('users', 'email')->ignore($this->record),
    ])
    ->validationMessages([
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already taken.',
    ]);

// Conditional validation
TextInput::make('password')
    ->password()
    ->required(fn ($get) => $get('change_password'))
    ->minLength(8)
    ->confirmed();

TextInput::make('password_confirmation')
    ->password()
    ->required(fn ($get) => $get('change_password'));
```

#### 4.5 Custom Fields
**Creating Custom Fields:**
- Extend `Filament\Forms\Components\Field`
- Custom rendering logic
- Supports validation and state management
- Reusable components

**Example Code:**
```php
use Filament\Forms\Components\Field;

class CustomField extends Field
{
    protected string $view = 'forms.components.custom-field';

    public function getState(): mixed
    {
        return $this->evaluate($this->state);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function ($state) {
            // Custom state handling logic
        });
    }
}
```

### 5. Infolists

#### 5.1 Overview
**Brand New Feature in v4:**
- Specialized system for read-only display of record information
- Replaces traditional detail views with better user experience
- Supports multiple layouts and styles, highly customizable
- Based on Schema system, shares components with forms and infolists
- Supports responsive design and conditional display
- Integrated into panel resources, relation managers, and action modals
- Suitable for custom Livewire applications

#### 5.2 Core Concepts

##### 5.2.1 Defining Entries
**Entries are the basic building blocks of infolists:**
```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->label('Full Name')
    ->size(TextEntry\TextEntrySize::Large);
```

##### 5.2.2 Entry Content/State
**Setting entry content and state:**

**Direct use of model attributes:**
```php
TextEntry::make('name')  // Automatically uses the model's name attribute
```

**Custom state:**
```php
TextEntry::make('display_name')
    ->state(fn ($record) => "{$record->first_name} {$record->last_name}")
```

**Default state:**
```php
TextEntry::make('status')
    ->default('No status set')
```

##### 5.2.3 Entry Labels
**Setting and customizing entry labels:**

**Custom labels:**
```php
TextEntry::make('email')
    ->label('Email Address')
```

**Hide labels:**
```php
TextEntry::make('name')
    ->label(false)
```

**Dynamic labels:**
```php
TextEntry::make('name')
    ->label(fn (string $state): string => str_contains($state, ' ') ? 'Full name' : 'Name')
```

##### 5.2.4 Click Entry to Open URL
**Adding click behavior to entries:**
```php
TextEntry::make('email')
    ->url(fn ($state) => "mailto:{$state}")
    ->openUrlInNewTab();

TextEntry::make('website')
    ->url(fn ($state) => $state)
    ->openUrlInNewTab();
```

##### 5.2.5 Hiding Entries
**Conditionally hiding entries:**

**Based on record state:**
```php
TextEntry::make('admin_notes')
    ->hidden(fn ($record) => $record->role !== 'admin')
```

**Based on operation type:**
```php
TextEntry::make('internal_id')
    ->hidden(fn (string $operation) => $operation === 'view')
```

**Based on entry state:**
```php
TextEntry::make('sensitive_data')
    ->hidden(fn ($state) => empty($state))
```

##### 5.2.6 Inline Labels
**Using inline label styles:**

**Single entry:**
```php
TextEntry::make('name')
    ->inlineLabel()
```

**Global settings:**
```php
// In service provider
TextEntry::configureUsing(function (TextEntry $entry): void {
    $entry->inlineLabel();
});
```

##### 5.2.7 Entry Tooltips
**Adding tooltips to entries:**
```php
TextEntry::make('status')
    ->tooltip('Current user status')
    ->tooltipIcon('heroicon-m-information-circle');
```

##### 5.2.8 Content Alignment
**Controlling entry content alignment:**
```php
TextEntry::make('amount')
    ->alignStart()    // Left align
    ->alignCenter()   // Center align
    ->alignEnd()      // Right align
    ->alignJustify(); // Justify align
```

##### 5.2.9 Adding Extra Content
**Adding extra content at different positions of entries:**

**Above the label:**
```php
TextEntry::make('name')
    ->extraAttributes(['class' => 'font-bold'])
    ->extraContentAbove(fn ($state) => view('components.user-avatar', ['user' => $state]));
```

**Before the label:**
```php
TextEntry::make('email')
    ->extraContentBefore(fn ($state) => view('components.email-icon'));
```

**After the label:**
```php
TextEntry::make('status')
    ->extraContentAfter(fn ($state) => view('components.status-indicator', ['status' => $state]));
```

**Below the label:**
```php
TextEntry::make('description')
    ->extraContentBelow(fn ($state) => view('components.description-helper'));
```

**Above the content:**
```php
TextEntry::make('content')
    ->extraContentAbove(fn ($state) => view('components.content-header'));
```

**Before the content:**
```php
TextEntry::make('price')
    ->extraContentBefore(fn ($state) => '$')
```

**After the content:**
```php
TextEntry::make('price')
    ->extraContentAfter(fn ($state) => ' USD')
```

##### 5.2.10 Adding HTML Attributes
**Adding custom HTML attributes to entries:**

**For the entry itself:**
```php
TextEntry::make('name')
    ->extraAttributes([
        'class' => 'font-bold text-primary',
        'data-testid' => 'user-name',
    ]);
```

**For the entry wrapper:**
```php
TextEntry::make('slug')
    ->extraEntryWrapperAttributes(['class' => 'components-locked']);
```

**Dynamic attributes:**
```php
TextEntry::make('status')
    ->extraAttributes(function ($state, $record) {
        return [
            'class' => $state === 'active' ? 'text-success' : 'text-danger',
            'data-status' => $state,
        ];
    });
```

**Merging attributes:**
```php
TextEntry::make('name')
    ->extraAttributes(['class' => 'base-style'])
    ->extraAttributes(['class' => 'additional-style'], merge: true);
```

#### 5.3 Utility Injection

##### 5.3.1 Inject Current Entry State
**Accessing the current value of an entry:**
```php
TextEntry::make('name')
    ->label(fn (string $state): string => "Name: {$state}")
    ->color(fn (string $state): string => $state === 'Admin' ? 'danger' : 'primary');
```

##### 5.3.2 Inject Other Entry States
**Getting values from other entries:**
```php
use Filament\Schemas\Components\Utilities\Get;

TextEntry::make('full_name')
    ->state(function (Get $get) {
        $firstName = $get('first_name');
        $lastName = $get('last_name');
        return "{$firstName} {$lastName}";
    });
```

##### 5.3.3 Inject Eloquent Record
**Accessing the current Eloquent record:**
```php
use Illuminate\Database\Eloquent\Model;

TextEntry::make('user_info')
    ->state(function (?Model $record) {
        return $record ? "User ID: {$record->id}" : 'No user';
    });
```

##### 5.3.4 Inject Current Operation
**Checking the current operation type:**
```php
TextEntry::make('editable_field')
    ->hidden(fn (string $operation) => $operation === 'view');
```

##### 5.3.5 Inject Livewire Component Instance
**Accessing Livewire component:**
```php
use Livewire\Component;

TextEntry::make('component_data')
    ->state(function (Component $livewire) {
        return $livewire->someProperty;
    });
```

##### 5.3.6 Inject Entry Instance
**Accessing entry component instance:**
```php
use Filament\Infolists\Components\Entry;

TextEntry::make('dynamic_label')
    ->label(function (Entry $component) {
        return $component->getName() . ' Label';
    });
```

##### 5.3.7 Inject Multiple Utilities
**Combining multiple utilities:**
```php
use App\Models\User;
use Filament\Schemas\Components\Utilities\Get;
use Livewire\Component as Livewire;

TextEntry::make('complex_data')
    ->state(function (Livewire $livewire, Get $get, User $record) {
        $email = $get('email');
        $componentData = $livewire->someProperty;
        $userId = $record->id;
        
        return "Email: {$email}, Component: {$componentData}, User: {$userId}";
    });
```

##### 5.3.8 Inject Laravel Container Dependencies
**Injecting Laravel services:**
```php
use App\Models\User;
use Illuminate\Http\Request;

TextEntry::make('request_data')
    ->state(function (Request $request, User $record) {
        return "IP: {$request->ip()}, User: {$record->name}";
    });
```

#### 5.4 Global Settings
**Setting default behavior for all entries:**

```php
// In AppServiceProvider's boot() method
use Filament\Infolists\Components\TextEntry;

TextEntry::configureUsing(function (TextEntry $entry): void {
    $entry->words(10);  // Limit all text entries to 10 words
});

// Can still override in individual entries
TextEntry::make('full_description')
    ->words(null);  // No word limit
```

#### 5.5 Best Practices

##### 5.5.1 Performance Optimization
- **Avoid N+1 Queries**: Use appropriate database queries
- **Conditional Rendering**: Use conditional display reasonably
- **Cache Calculations**: Cache complex calculations

##### 5.5.2 User Experience
- **Clear Labels**: Use descriptive labels
- **Appropriate Formatting**: Format data to improve readability
- **Consistent Styling**: Maintain visual consistency

##### 5.5.3 Accessibility
- **Semantic Labels**: Use appropriate HTML semantics
- **Keyboard Navigation**: Ensure keyboard accessibility
- **Screen Readers**: Provide appropriate ARIA labels

#### 5.6 Basic Entry Components

##### 5.6.1 Text Entry
**Features:**
- Text content display and formatting
- Supports multiple text sizes and styles
- Auto-wrapping and truncation
- Supports HTML content
- Copyable text content

**Example Code:**
```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->label('Full Name')
    ->size(TextEntry\TextEntrySize::Large)
    ->weight('bold')
    ->color('primary');

TextEntry::make('description')
    ->label('Description')
    ->html()
    ->markdown()
    ->prose()
    ->limit(200)
    ->copyable();

TextEntry::make('email')
    ->label('Email Address')
    ->url(fn ($state) => "mailto:{$state}")
    ->icon('heroicon-m-envelope');
```

##### 5.6.2 Icon Entry
**Features:**
- Icon display and status indication
- Supports multiple icon libraries
- Custom icon colors and sizes
- Conditional icon display

**Example Code:**
```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('status')
    ->label('Status')
    ->icon(fn (string $state): string => match ($state) {
        'active' => 'heroicon-o-check-circle',
        'inactive' => 'heroicon-o-x-circle',
        'pending' => 'heroicon-o-clock',
        default => 'heroicon-o-question-mark-circle',
    })
    ->color(fn (string $state): string => match ($state) {
        'active' => 'success',
        'inactive' => 'danger',
        'pending' => 'warning',
        default => 'gray',
    })
    ->size('lg');
```

##### 5.6.3 Image Entry
**Features:**
- Image display and preview
- Supports multiple image formats
- Custom image sizes and styles
- Circular, square, and other shape options
- Click to zoom functionality

**Example Code:**
```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('avatar')
    ->label('Profile Picture')
    ->circular()
    ->size(80)
    ->extraImgAttributes(['class' => 'object-cover']);

ImageEntry::make('banner')
    ->label('Banner Image')
    ->square()
    ->size(200)
    ->openUrlInNewTab()
    ->extraImgAttributes(['class' => 'rounded-lg shadow-md']);
```

##### 5.6.4 Color Entry
**Features:**
- Color display and preview
- Supports multiple color formats
- Copyable color values
- Custom color display styles

**Example Code:**
```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('theme_color')
    ->label('Theme Color')
    ->copyable()
    ->copyMessage('Color copied to clipboard!')
    ->size('lg');
```

##### 5.6.5 Code Entry
**Features:**
- Code syntax highlighting display
- Supports multiple programming languages
- Line number display
- Code copy functionality

**Example Code:**
```php
use Filament\Infolists\Components\CodeEntry;

CodeEntry::make('custom_css')
    ->label('Custom CSS')
    ->language('css')
    ->copyable()
    ->lineNumbers();
```

##### 5.6.6 Key Value Entry
**Features:**
- Key-value pair data display
- Supports nested structures
- Custom key and value formats
- Conditional display

**Example Code:**
```php
use Filament\Infolists\Components\KeyValueEntry;

KeyValueEntry::make('metadata')
    ->label('Custom Metadata')
    ->keyLabel('Property')
    ->valueLabel('Value')
    ->columnSpanFull();
```

##### 5.6.7 Repeatable Entry
**Features:**
- Repeatable data structure display
- Supports list and table formats
- Custom item labels
- Conditional display

**Example Code:**
```php
use Filament\Infolists\Components\RepeatableEntry;

RepeatableEntry::make('phone_numbers')
    ->label('Phone Numbers')
    ->schema([
        TextEntry::make('type')
            ->label('Type')
            ->badge(),
        TextEntry::make('number')
            ->label('Number')
            ->url(fn ($state) => "tel:{$state}"),
    ])
    ->contained(false);
```

#### 5.7 Advanced Features

##### 5.7.1 Conditional Display
**Dynamic display based on data conditions:**
```php
TextEntry::make('status')
    ->label('Status')
    ->visible(fn ($record) => $record->status !== 'draft')
    ->color(fn ($state) => $state === 'published' ? 'success' : 'warning');
```

##### 5.7.2 Custom Formatting
**Data formatting and transformation:**
```php
TextEntry::make('created_at')
    ->label('Created At')
    ->dateTime('M j, Y g:i A')
    ->timezone('UTC');

TextEntry::make('price')
    ->label('Price')
    ->money('USD')
    ->color(fn ($state) => $state > 100 ? 'success' : 'gray');
```

##### 5.7.3 Relationship Data Display
**Displaying related model data:**
```php
TextEntry::make('user.name')
    ->label('Created By')
    ->url(fn ($record) => route('admin.users.edit', $record->user));

TextEntry::make('category.name')
    ->label('Category')
    ->badge()
    ->color('primary');
```

##### 5.7.4 Custom Entries
**Creating completely custom entries:**
```php
use Filament\Infolists\Components\Entry;

class CustomEntry extends Entry
{
    protected string $view = 'infolists.components.custom-entry';

    public function getState(): mixed
    {
        return $this->evaluate($this->state);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function ($state) {
            // Custom state handling logic
        });
    }
}
```

#### 5.8 Layout and Organization

##### 5.8.1 Using Schema Components
**Layout components shared with forms:**
```php
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;

Section::make('Personal Information')
    ->schema([
        TextEntry::make('name'),
        TextEntry::make('email'),
        TextEntry::make('phone'),
    ])
    ->columns(3);

Tabs::make('User Details')
    ->tabs([
        Tabs\Tab::make('Basic Info')
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
            ]),
        Tabs\Tab::make('Address')
            ->schema([
                TextEntry::make('street'),
                TextEntry::make('city'),
                TextEntry::make('postal_code'),
            ]),
    ]);
```

##### 5.8.2 Responsive Design
**Supports responsive layouts:**
```php
Grid::make()
    ->columns([
        'default' => 1,
        'md' => 2,
        'xl' => 3,
    ])
    ->schema([
        TextEntry::make('name')
            ->columnSpan([
                'default' => 1,
                'md' => 2,
            ]),
        TextEntry::make('email')
            ->columnSpan([
                'default' => 1,
                'md' => 1,
            ]),
    ]);
```

#### 5.9 Best Practices

##### 5.9.1 Performance Optimization
- Use appropriate data loading strategies
- Avoid N+1 query problems
- Use conditional display reasonably

##### 5.9.2 User Experience
- Provide clear labels and descriptions
- Use appropriate colors and icons
- Ensure responsive design

##### 5.9.3 Accessibility
- Provide appropriate ARIA labels
- Ensure keyboard navigation support
- Use sufficient color contrast

### 6. Schema System

#### 6.1 Overview
**New Concept in v4:**
- Unified page structure definition for forms, infolists, and page layouts
- Supports multiple layout types, providing flexible page organization
- Highly customizable, supports completely custom components
- Based on Livewire 3 and Alpine.js, provides responsive interactive experience

#### 6.2 Layout Components

##### 6.2.1 Grid System
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

##### 6.2.2 Container Queries
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

##### 6.2.3 Basic Layout Components

###### 6.2.3.1 Grid Component
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

###### 6.2.3.2 Flex Component
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

###### 6.2.3.3 Fieldset Component
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

###### 6.2.3.4 Section Component
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

#### 6.2.4 Grid Column Control

##### 6.2.4.1 Column Span
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

##### 6.2.4.2 Column Start
**Control the starting column position of a component:**
```php
TextInput::make('name')
    ->columnStart([
        'default' => 1,
        'md' => 2,  // Start from column 2
        'xl' => 3,  // Start from column 3
    ]);
```

##### 6.2.4.3 Column Order
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

#### 6.2.5 Responsive Grid Layout Example
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

#### 6.2.6 Container Queries
**Responsive layout based on container size:**

##### 6.2.6.1 Basic Container Queries
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

##### 6.2.6.2 Container Queries with Column Ordering
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

##### 6.2.6.3 Fallback Breakpoints for Older Browsers
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

#### 6.2.7 Adding Extra HTML Attributes
**Add custom attributes to layout components:**

##### 6.2.7.1 Static Attributes
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

##### 6.2.7.2 Dynamic Attributes
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

##### 6.2.7.3 Merging Attributes
```php
Section::make('Merged Section')
    ->extraAttributes(['class' => 'base-style'])
    ->extraAttributes(['class' => 'additional-style'], merge: true)
    ->schema([
        TextInput::make('name'),
        TextInput::make('email'),
    ]);
```

#### 6.2.8 Layout Best Practices

##### 6.2.8.1 Responsive Design Principles
- **Mobile First**: Start designing from the smallest screen
- **Progressive Enhancement**: Add more columns for larger screens
- **Consistency**: Maintain visual consistency across devices
- **Readability**: Ensure text remains readable on small screens

##### 6.2.8.2 Performance Optimization
- **Avoid Over-nesting**: Limit grid nesting depth
- **Reasonable Column Usage**: Avoid using too many columns on small screens
- **Conditional Rendering**: Use conditional display to reduce unnecessary components

##### 6.2.8.3 Accessibility
- **Keyboard Navigation**: Ensure all components are accessible via keyboard
- **Screen Readers**: Provide appropriate ARIA labels
- **Color Contrast**: Ensure sufficient color contrast

##### 6.2.8.4 Code Organization
- **Logical Grouping**: Organize related fields together
- **Naming Conventions**: Use consistent naming conventions
- **Comments**: Add comments for complex layouts

#### 6.3 Section Components
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

#### 6.4 Tab Components
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

#### 6.5 Wizard Components
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

#### 6.6 Prime Components
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

#### 6.7 Custom Components
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

#### 6.8 Utility Injection
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

### 7. Action System

#### 7.1 Overview
**v4 Action System Features:**
- Supports multiple action types and trigger methods
- Modal and full-page action support
- Action grouping and organization functionality
- Highly customizable action logic
- Supports bulk actions and single record actions
- Real-time feedback and notification system

#### 7.2 Basic Action Types

##### 7.2.1 Create Action
**Features:**
- Form action for creating new records
- Supports custom forms and validation
- Customizable redirects and notifications
- Supports post-creation action chains

**Example Code:**
```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->label('Create New User')
    ->icon('heroicon-o-plus')
    ->form([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
        Select::make('role')->options([
            'admin' => 'Administrator',
            'user' => 'User',
        ])->required(),
    ])
    ->action(function (array $data) {
        $user = User::create($data);
        
        // Send welcome email
        Mail::to($user->email)->send(new WelcomeMail($user));
        
        return $user;
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('User created successfully')
            ->body('The user has been created and a welcome email has been sent.')
    )
    ->after(function ($record) {
        // Post-creation actions
        activity()->log("Created user: {$record->name}");
    });
```

##### 7.2.2 Edit Action
**Features:**
- Form action for editing existing records
- Supports custom forms and validation
- Customizable save logic
- Supports post-edit actions

**Example Code:**
```php
use Filament\Actions\EditAction;

EditAction::make()
    ->label('Edit User')
    ->icon('heroicon-o-pencil')
    ->form([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
        Select::make('role')->options([
            'admin' => 'Administrator',
            'user' => 'User',
        ])->required(),
    ])
    ->action(function (array $data, $record) {
        $record->update($data);
        
        // Log changes
        activity()->log("Updated user: {$record->name}");
        
        return $record;
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('User updated successfully')
            ->body('The user information has been updated.')
    );
```

##### 7.2.3 View Action
**Features:**
- Read-only record display action
- Uses Infolist components to display data
- Supports custom layouts and styles
- Can add custom action buttons

**Example Code:**
```php
use Filament\Actions\ViewAction;

ViewAction::make()
    ->label('View Details')
    ->icon('heroicon-o-eye')
    ->infolist([
        TextEntry::make('name')->label('Full Name'),
        TextEntry::make('email')->label('Email Address'),
        TextEntry::make('role')->label('Role')->badge(),
        TextEntry::make('created_at')->label('Created At')->dateTime(),
    ])
    ->modalHeading('User Details')
    ->modalDescription('View detailed information about this user.')
    ->modalSubmitAction(false) // Hide submit button
    ->modalCancelActionLabel('Close');
```

##### 7.2.4 Delete Action
**Features:**
- Confirmation action for deleting records
- Supports soft delete and hard delete
- Customizable delete logic
- Supports bulk deletion

**Example Code:**
```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->label('Delete User')
    ->icon('heroicon-o-trash')
    ->color('danger')
    ->requiresConfirmation()
    ->modalHeading('Delete User')
    ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
    ->modalSubmitActionLabel('Yes, delete user')
    ->action(function ($record) {
        // Custom delete logic
        if ($record->hasActiveOrders()) {
            throw new \Exception('Cannot delete user with active orders.');
        }
        
        $record->delete();
        
        // Log delete operation
        activity()->log("Deleted user: {$record->name}");
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('User deleted successfully')
            ->body('The user has been permanently deleted.')
    );
```

##### 7.2.5 Replicate Action
**Features:**
- Duplicates existing records
- Supports custom replication logic
- Can select which fields to copy
- Supports relationship data replication

**Example Code:**
```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()
    ->label('Duplicate User')
    ->icon('heroicon-o-document-duplicate')
    ->form([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required(),
        Checkbox::make('copy_permissions')->label('Copy user permissions'),
    ])
    ->action(function (array $data, $record) {
        $newUser = $record->replicate();
        $newUser->name = $data['name'];
        $newUser->email = $data['email'];
        $newUser->save();
        
        // Copy permissions
        if ($data['copy_permissions']) {
            $newUser->permissions()->attach($record->permissions);
        }
        
        return $newUser;
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('User duplicated successfully')
            ->body('A new user has been created based on the original.')
    );
```

##### 7.2.6 Force Delete Action
**Features:**
- Permanently deletes soft-deleted records
- Bypasses soft delete protection
- Supports bulk force deletion
- Customizable delete logic

**Example Code:**
```php
use Filament\Actions\ForceDeleteAction;

ForceDeleteAction::make()
    ->label('Permanently Delete')
    ->icon('heroicon-o-trash')
    ->color('danger')
    ->requiresConfirmation()
    ->modalHeading('Permanently Delete User')
    ->modalDescription('This will permanently delete the user and all associated data. This action cannot be undone.')
    ->modalSubmitActionLabel('Yes, permanently delete')
    ->visible(fn ($record) => $record->trashed())
    ->action(function ($record) {
        $record->forceDelete();
        
        activity()->log("Permanently deleted user: {$record->name}");
    });
```

##### 7.2.7 Restore Action
**Features:**
- Restores soft-deleted records
- Supports bulk restoration
- Customizable restore logic
- Automatically handles timestamps

**Example Code:**
```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->label('Restore User')
    ->icon('heroicon-o-arrow-path')
    ->color('success')
    ->visible(fn ($record) => $record->trashed())
    ->action(function ($record) {
        $record->restore();
        
        activity()->log("Restored user: {$record->name}");
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('User restored successfully')
            ->body('The user has been restored and is now active again.')
    );
```

##### 7.2.8 Import Action
**Features:**
- Bulk import of records
- Supports multiple file formats
- Customizable import logic
- Error handling and reporting

**Example Code:**
```php
use Filament\Actions\ImportAction;

ImportAction::make()
    ->label('Import Users')
    ->icon('heroicon-o-arrow-up-tray')
    ->form([
        FileUpload::make('file')
            ->label('CSV File')
            ->acceptedFileTypes(['text/csv'])
            ->required(),
        Checkbox::make('skip_duplicates')
            ->label('Skip duplicate emails')
            ->default(true),
    ])
    ->action(function (array $data) {
        $file = $data['file'];
        $skipDuplicates = $data['skip_duplicates'];
        
        $imported = 0;
        $skipped = 0;
        
        foreach (Csv::fromFile($file) as $row) {
            if ($skipDuplicates && User::where('email', $row['email'])->exists()) {
                $skipped++;
                continue;
            }
            
            User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'role' => $row['role'] ?? 'user',
            ]);
            
            $imported++;
        }
        
        return [
            'imported' => $imported,
            'skipped' => $skipped,
        ];
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('Import completed')
            ->body(fn ($data) => "Imported {$data['imported']} users, skipped {$data['skipped']} duplicates.")
    );
```

##### 7.2.9 Export Action
**Features:**
- Bulk export of records
- Supports multiple file formats
- Customizable export fields
- Filtering and sorting support

**Example Code:**
```php
use Filament\Actions\ExportAction;

ExportAction::make()
    ->label('Export Users')
    ->icon('heroicon-o-arrow-down-tray')
    ->form([
        Select::make('format')
            ->options([
                'csv' => 'CSV',
                'xlsx' => 'Excel',
                'json' => 'JSON',
            ])
            ->default('csv')
            ->required(),
        CheckboxList::make('fields')
            ->options([
                'name' => 'Name',
                'email' => 'Email',
                'role' => 'Role',
                'created_at' => 'Created At',
            ])
            ->default(['name', 'email', 'role'])
            ->required(),
    ])
    ->action(function (array $data) {
        $users = User::select($data['fields'])->get();
        
        return match ($data['format']) {
            'csv' => $users->toCsv(),
            'xlsx' => $users->toExcel(),
            'json' => $users->toJson(),
        };
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('Export completed')
            ->body('Your file has been generated and is ready for download.')
    );
```

#### 7.3 Advanced Features

##### 7.3.1 Action Groups
**Organize related actions:**
```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    ViewAction::make(),
    EditAction::make(),
    DeleteAction::make(),
])
    ->label('Actions')
    ->icon('heroicon-m-ellipsis-vertical')
    ->color('gray')
    ->dropdownPlacement('bottom-end');
```

##### 7.3.2 Bulk Actions
**Execute actions on multiple records:**
```php
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;

BulkActionGroup::make([
    BulkAction::make('activate')
        ->label('Activate Selected')
        ->icon('heroicon-o-check-circle')
        ->color('success')
        ->action(function ($records) {
            $records->each->update(['is_active' => true]);
        }),
    BulkAction::make('deactivate')
        ->label('Deactivate Selected')
        ->icon('heroicon-o-x-circle')
        ->color('danger')
        ->action(function ($records) {
            $records->each->update(['is_active' => false]);
        }),
    BulkAction::make('delete')
        ->label('Delete Selected')
        ->icon('heroicon-o-trash')
        ->color('danger')
        ->requiresConfirmation()
        ->action(function ($records) {
            $records->each->delete();
        }),
]);
```

##### 7.3.3 Conditional Actions
**Display actions based on record state:**
```php
EditAction::make()
    ->visible(fn ($record) => $record->status !== 'archived')
    ->disabled(fn ($record) => $record->is_locked);

DeleteAction::make()
    ->visible(fn ($record) => auth()->user()->can('delete', $record))
    ->requiresConfirmation(fn ($record) => $record->has_important_data);
```

##### 7.3.4 Custom Actions
**Create completely custom actions:**
```php
use Filament\Actions\Action;

Action::make('send_notification')
    ->label('Send Notification')
    ->icon('heroicon-o-bell')
    ->color('warning')
    ->form([
        TextInput::make('subject')->required(),
        Textarea::make('message')->required(),
    ])
    ->action(function (array $data, $record) {
        $record->notify(new CustomNotification($data['subject'], $data['message']));
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('Notification sent')
            ->body('The notification has been sent successfully.')
    );
```

#### 7.4 Best Practices

##### 7.4.1 Performance Optimization
- Use appropriate database queries
- Avoid N+1 query problems
- Use bulk operations reasonably

##### 7.4.2 User Experience
- Provide clear confirmation dialogs
- Use appropriate icons and colors
- Provide detailed success/error messages

##### 7.4.3 Security
- Implement appropriate permission checks
- Validate all user input
- Log important operations

### 8. Notification System

#### 8.1 Overview
**v4 New Features:**
- **Database Notifications**: Persistent notification storage
- **Broadcast Notifications**: Real-time notification push
- Supports multiple notification types

#### 8.2 Notification Types
- Success notifications
- Error notifications
- Warning notifications
- Information notifications

### 9. Widget System

#### 9.1 Overview
**v4 Enhanced Features:**
- **Stats Overview Widget**: Numeric statistics display
- **Chart Widget**: Various chart types
- Fully customizable widget support

#### 9.2 Widget Types
- **Stats Overview Widget**: Statistics overview
- **Chart Widget**: Chart widget
- **Custom Widget**: Custom widget

### 10. Navigation System

#### 10.1 Overview
**v4 New Features:**
- **Custom Pages**: Fully customizable pages
- **User Menu**: Customizable user dropdown menu
- **Clusters**: Grouping of related pages

### 11. User Management

#### 11.1 Overview
**v4 Enhanced Features:**
- **Multi-factor Authentication**: Supports 2FA
- **Multi-tenancy**: Supports multi-tenant architecture

### 12. Style Customization

#### 12.1 CSS Hooks
- Provides standardized CSS class names
- Supports dark mode
- Responsive design support

#### 12.2 Color System
- Customizable theme colors
- Supports CSS variables
- Consistent color naming conventions

#### 12.3 Icon System
- Supports multiple icon libraries
- Customizable icons
- Consistent icon usage conventions

### 13. Advanced Features

#### 13.1 Render Hooks
- Custom component rendering points
- Flexible content injection
- Powerful extension capabilities

#### 13.2 Resource Registration
- Automatic resource discovery
- Manual resource registration
- Conditional resource display

#### 13.3 File Generation
- Automatic resource file generation
- Custom generators
- Batch file operations

### 14. Testing

#### 14.1 Testing Resources
- Resource functionality testing
- Form validation testing
- Action testing

#### 14.2 Testing Tables
- Column testing
- Filter testing
- Action testing

#### 14.3 Testing Schemas
- Form testing
- Validation testing
- Custom component testing

### 15. Plugin System

#### 15.1 Plugin Development
- Panel plugins
- Standalone plugins
- Plugin marketplace

#### 15.2 Building Plugins
- Plugin structure
- Resource registration
- Asset management

### 16. Component Library

#### 16.1 Blade Components
- Avatar
- Badge
- Breadcrumbs
- Button
- Checkbox
- Dropdown
- Fieldset
- Icon button
- Input wrapper
- Input
- Link
- Loading indicator
- Modal
- Pagination
- Section
- Select
- Tabs

#### 16.2 Component Rendering
- Render actions in Livewire components
- Render forms in Blade views
- Render infolists in Blade views
- Render notifications outside panels
- Render schemas in Blade views
- Render tables in Blade views
- Render widgets in Blade views

### 17. Production Deployment

#### 17.1 Performance Optimization
- Resource compression
- Caching strategies
- Database optimization

#### 17.2 Security Considerations
- Authentication configuration
- Permission management
- Data validation

### 18. Upgrade Guide

#### 18.1 Upgrading from v3
- Major changes overview
- Migration steps
- Common issue resolution

#### 18.2 Compatibility
- PHP version requirements
- Laravel version requirements
- Browser support

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

## Common Issues

### 1. Performance Issues
- Check database queries
- Optimize resource loading
- Use appropriate caching

### 2. Style Issues
- Check CSS conflicts
- Use correct CSS class names
- Ensure responsive design

### 3. Functionality Issues
- Check configuration settings
- Review error logs
- Refer to official documentation

## Reference Resources

- [Official Documentation](https://filamentphp.com/docs/4.x)
- [GitHub Repository](https://github.com/filamentphp/filament)
- [Community Forum](https://filamentphp.com/community)
- [Plugin Marketplace](https://filamentphp.com/plugins)

## Conclusion

Filament v4 is a powerful and flexible Laravel admin panel builder. Through this documentation, you should be able to understand the new features and improvements in v4 and start building your own admin applications.

Remember, Filament is an active open-source project with regular updates and new features. It is recommended to check the official documentation regularly for the latest information.

### 19. Managing Relationships

#### 19.1 Overview
**Choosing the Right Tool:**
Filament provides multiple ways to manage relationships. The choice of which feature to use depends on the type of relationship you want to manage and the UI you need.

#### 19.2 Relationship Management Tools

##### 19.2.1 Relation Managers
**Suitable for:** `HasMany`, `HasManyThrough`, `BelongsToMany`, `MorphMany`, `MorphToMany` relationships
**Features:** Interactive tables below resource forms

**Create Relation Manager:**
```bash
php artisan make:filament-relation-manager CategoryResource posts title
```

**Basic Structure:**
```php
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';
    protected static ?string $recordTitleAttribute = 'title';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }
}
```

**Register Relation Manager:**
```php
public static function getRelations(): array
{
    return [
        PostsRelationManager::class,
    ];
}
```

##### 19.2.2 Selectors and Checkbox Lists
**Suitable for:** `BelongsTo`, `BelongsToMany` relationships
**Features:** Select from existing records or create new records

**BelongsTo Relationship:**
```php
use Filament\Forms\Components\Select;

Select::make('category_id')
    ->relationship('category', 'name')
    ->searchable()
    ->preload()
    ->createOptionForm([
        TextInput::make('name')->required(),
        TextInput::make('slug')->required(),
    ]);
```

**BelongsToMany Relationship:**
```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('tags')
    ->relationship('tags', 'name')
    ->searchable()
    ->preload()
    ->bulkToggleable();
```

##### 19.2.3 Repeaters
**Suitable for:** CRUD multiple related records within owner form
**Features:** Dynamically add/remove field groups

```php
use Filament\Forms\Components\Repeater;

Repeater::make('phone_numbers')
    ->schema([
        Select::make('type')
            ->options([
                'mobile' => 'Mobile',
                'home' => 'Home',
                'work' => 'Work',
            ])
            ->required(),
        TextInput::make('number')
            ->tel()
            ->required(),
    ])
    ->defaultItems(1)
    ->reorderableWithButtons()
    ->collapsible();
```

##### 19.2.4 Layout Form Components
**Suitable for:** Save form fields to a single relationship
**Features:** Use Section or Fieldset for grouping

```php
use Filament\Forms\Components\Section;

Section::make('Address Information')
    ->schema([
        TextInput::make('address.street'),
        TextInput::make('address.city'),
        TextInput::make('address.postal_code'),
    ])
    ->columns(3);
```

#### 19.3 Relation Manager Features

##### 19.3.1 Custom URL Parameters
```php
protected static ?string $slug = 'posts';
```

##### 19.3.2 Read-only Mode
```php
protected static bool $isReadOnly = true;
```

##### 19.3.3 Handle Soft Deletes
```php
protected static bool $hasAssociateAction = false;
protected static bool $hasDissociateAction = false;
```

##### 19.3.4 Custom Queries
```php
public function table(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published'))
        ->columns([
            // ...
        ]);
}
```

#### 19.4 Relation Pages
**Alternative to Relation Managers:**
- Use `ManageRelatedRecords` pages
- Suitable for resource sub-navigation
- Keep relationship management functionality separate from editing/viewing owner records

**Create Relation Page:**
```bash
php artisan make:filament-page ManageCustomerAddresses --resource=CustomerResource --type=ManageRelatedRecords
```

**Register Relation Page:**
```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListCustomers::route('/'),
        'create' => Pages\CreateCustomer::route('/create'),
        'view' => Pages\ViewCustomer::route('/{record}'),
        'edit' => Pages\EditCustomer::route('/{record}/edit'),
        'addresses' => Pages\ManageCustomerAddresses::route('/{record}/addresses'),
    ];
}
```

**Add to Resource Sub-navigation:**
```php
public static function getRecordSubNavigation(Page $page): array
{
    return $page->generateNavigationItems([
        // ...
        Pages\ManageCustomerAddresses::class,
    ]);
}
```

#### 19.5 Passing Properties to Relation Managers
```php
public static function getRelations(): array
{
    return [
        CommentsRelationManager::make([
            'status' => 'approved',
        ]),
    ];
}
```

**Receive in Relation Manager:**
```php
class CommentsRelationManager extends RelationManager
{
    public string $status;

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $this->status))
            ->columns([
                // ...
            ]);
    }
}
```

#### 19.6 Disable Lazy Loading
```php
protected static bool $isLazy = false;
```

### 20. Resources Overview

#### 20.1 Resource Basics
**Resources are complete CRUD interfaces for managing Eloquent models:**
- **List** - Paginated table displaying all records
- **Create** - Form for creating new records
- **Edit** - Form for editing existing records
- **View** - Read-only record display

#### 20.2 Create Resource
```bash
php artisan make:filament-resource User
```

**Basic Resource Structure:**
```php
use Filament\Resources\Resource;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
```

#### 20.3 Resource Configuration

##### 20.3.1 Navigation Configuration
```php
protected static ?string $navigationIcon = 'heroicon-o-users';
protected static ?string $navigationGroup = 'User Management';
protected static ?int $navigationSort = 1;
protected static ?string $navigationLabel = 'Users';
protected static ?string $navigationBadge = 'New';
protected static ?string $navigationBadgeTooltip = 'New users this week';
```

##### 20.3.2 Model Configuration
```php
protected static ?string $model = User::class;
protected static ?string $slug = 'users';
protected static ?string $recordTitleAttribute = 'name';
```

##### 20.3.3 Permission Configuration
```php
protected static bool $shouldRegisterNavigation = true;
protected static bool $shouldSkipAuthorization = false;
```

#### 20.4 Custom Pages
```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListUsers::route('/'),
        'create' => Pages\CreateUser::route('/create'),
        'view' => Pages\ViewUser::route('/{record}'),
        'edit' => Pages\EditUser::route('/{record}/edit'),
        'settings' => Pages\UserSettings::route('/{record}/settings'),
    ];
}
```

### 21. Table Actions

#### 21.1 Action Types

##### 21.1.1 Row Actions
**Actions for individual records:**
```php
use Filament\Tables\Actions;

public function table(Table $table): Table
{
    return $table
        ->actions([
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ]);
}
```

##### 21.1.2 Header Actions
**Actions at the top of the table:**
```php
public function table(Table $table): Table
{
    return $table
        ->headerActions([
            Actions\CreateAction::make(),
            Actions\ImportAction::make(),
        ]);
}
```

##### 21.1.3 Bulk Actions
**Actions for multiple records:**
```php
use Filament\Tables\Actions\BulkActionGroup;

public function table(Table $table): Table
{
    return $table
        ->bulkActions([
            BulkActionGroup::make([
                Actions\BulkAction::make('activate')
                    ->label('Activate Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => true]);
                    }),
                Actions\BulkAction::make('deactivate')
                    ->label('Deactivate Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => false]);
                    }),
                Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### 21.2 Custom Actions

##### 21.2.1 Custom Action Buttons
```php
Actions\Action::make('send_notification')
    ->label('Send Notification')
    ->icon('heroicon-o-bell')
    ->color('warning')
    ->form([
        Forms\Components\TextInput::make('subject')->required(),
        Forms\Components\Textarea::make('message')->required(),
    ])
    ->action(function (array $data, $record) {
        $record->notify(new CustomNotification($data['subject'], $data['message']));
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('Notification sent')
            ->body('The notification has been sent successfully.')
    );
```

##### 21.2.2 Conditional Actions
```php
Actions\EditAction::make()
    ->visible(fn ($record) => $record->status !== 'archived')
    ->disabled(fn ($record) => $record->is_locked);
```

##### 21.2.3 Action Groups
```php
Actions\ActionGroup::make([
    Actions\ViewAction::make(),
    Actions\EditAction::make(),
    Actions\DeleteAction::make(),
])
    ->label('Actions')
    ->icon('heroicon-m-ellipsis-vertical')
    ->color('gray')
    ->dropdownPlacement('bottom-end');
```

#### 21.3 Action Configuration

##### 21.3.1 Modal Configuration
```php
Actions\EditAction::make()
    ->modalHeading('Edit User')
    ->modalDescription('Update user information')
    ->modalSubmitActionLabel('Save Changes')
    ->modalCancelActionLabel('Cancel')
    ->modalWidth('lg');
```

##### 21.3.2 Confirmation Dialog
```php
Actions\DeleteAction::make()
    ->requiresConfirmation()
    ->modalHeading('Delete User')
    ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
    ->modalSubmitActionLabel('Yes, delete user')
    ->modalCancelActionLabel('Cancel');
```

##### 21.3.3 Redirect Configuration
```php
Actions\CreateAction::make()
    ->redirect(route('admin.users.index'));

Actions\EditAction::make()
    ->redirect(fn ($record) => route('admin.users.view', $record));
```

### 22. Table Layout

#### 22.1 Responsive Layout
```php
public function table(Table $table): Table
{
    return $table
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
        ])
        ->columns([
            // ...
        ]);
}
```

#### 22.2 Custom Layout
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->columnSpan([
                    'default' => 1,
                    'md' => 2,
                ]),
            Tables\Columns\TextColumn::make('email')
                ->columnSpan([
                    'default' => 1,
                    'md' => 1,
                ]),
        ]);
}
```

#### 22.3 Table Styles
```php
public function table(Table $table): Table
{
    return $table
        ->striped()
        ->hover()
        ->bordered()
        ->compact();
}
```

#### 22.4 Empty State
```php
public function table(Table $table): Table
{
    return $table
        ->emptyStateHeading('No users found')
        ->emptyStateDescription('Create your first user to get started.')
        ->emptyStateIcon('heroicon-o-users')
        ->emptyStateActions([
            Tables\Actions\Action::make('create')
                ->label('Create user')
                ->url(route('admin.users.create'))
                ->icon('heroicon-o-plus')
                ->button(),
        ]);
}
```

### 23. Table Summaries

#### 23.1 Basic Summaries
```php
use Filament\Tables\Enums\FiltersLayout;

public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ]),
        ], layout: FiltersLayout::AboveContent)
        ->filtersFormColumns(3)
        ->filtersTriggerAction(
            fn (Tables\Actions\Action $action) => $action
                ->button()
                ->label('Filters'),
        );
}
```

#### 23.2 Custom Summaries
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->filters([
            Tables\Filters\Filter::make('created_from')
                ->form([
                    Forms\Components\DatePicker::make('created_from'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        );
                }),
        ]);
}
```

#### 23.3 Summary Layout
```php
public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::AboveContent) // AboveContent, AboveContentCollapsible, BelowContent, Dropdown
        ->filtersFormColumns(3)
        ->filtersTriggerAction(
            fn (Tables\Actions\Action $action) => $action
                ->button()
                ->label('Filters')
                ->icon('heroicon-o-funnel'),
        );
}
```

#### 23.4 Persistent Summaries
```php
public function table(Table $table): Table
{
    return $table
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->persist(),
        ]);
}
```

### 24. Relation Manager Customization

#### 24.1 Custom Relation Manager Title
```php
protected static ?string $title = 'Posts';

// Or dynamic title
public static function getTitle(Model $ownerRecord, string $pageClass): string
{
    return __('relation-managers.posts.title');
}
```

#### 24.2 Custom Record Title
```php
public function table(Table $table): Table
{
    return $table
        ->recordTitle(fn (Post $record): string => "{$record->title} ({$record->id})")
        ->columns([
            // ...
        ]);
}
```

#### 24.3 Relation Manager Grouping
```php
public static function getRelations(): array
{
    return [
        'content' => [
            PostsRelationManager::class,
            CommentsRelationManager::class,
        ],
        'settings' => [
            SettingsRelationManager::class,
        ],
    ];
}
```

#### 24.4 Conditional Relation Manager Display
```php
public static function getRelations(): array
{
    return [
        PostsRelationManager::class,
        CommentsRelationManager::class,
    ];
}

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->when(
            auth()->user()->role !== 'admin',
            fn (Builder $query) => $query->where('is_public', true),
        );
}
```

#### 24.5 Combine Relation Manager Tabs with Forms
```php
public function getContentTabLabel(): ?string
{
    return 'Content';
}

public function getContentTabIcon(): ?string
{
    return 'heroicon-o-document-text';
}

public function getContentTabBadge(): ?string
{
    return $this->getOwnerRecord()->posts()->count();
}
```

### 25. Advanced Table Features

#### 25.1 Row Grouping
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->grouping([
            'groupQuery' => fn (Builder $query, string $direction): Builder => $query->orderBy('created_at', $direction),
        ])
        ->defaultGroup('created_at');
}
```

#### 25.2 Custom Data
```php
public function table(Table $table): Table
{
    return $table
        ->query(
            User::query()
                ->select(['id', 'name', 'email', 'created_at'])
                ->where('is_active', true)
        )
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ]);
}
```

#### 25.3 Table Events
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->after(function ($record) {
                    // Post-edit actions
                    activity()->log("User {$record->name} was edited");
                }),
        ]);
}
```

### 26. Best Practices

#### 26.1 Performance Optimization
- Use appropriate database queries
- Avoid N+1 query problems
- Use eager loading appropriately
- Implement appropriate indexes

#### 26.2 User Experience
- Provide clear labels and descriptions
- Use appropriate icons and colors
- Ensure responsive design
- Provide useful empty states

#### 26.3 Security
- Implement appropriate permission checks
- Validate all user input
- Protect sensitive data
- Log important operations

#### 26.4 Code Organization
- Use appropriate namespaces
- Follow PSR standards
- Keep code clean
- Add appropriate comments


Remember, Filament is an active open-source project with regular updates and new features. It is recommended to check the official documentation regularly for the latest information.

### 27. Navigation System

#### 27.1 Overview
**Navigation System Features:**
- By default, Filament registers navigation items for each resource, custom page, and cluster
- These classes contain static properties and methods that you can override to configure navigation items
- If you want to add a second layer of navigation to your application, you can use clusters to group resources and pages together

#### 27.2 Custom Navigation Item Labels

**Custom Navigation Label:**
```php
protected static ?string $navigationLabel = 'Custom Navigation Label';
```

**Dynamic Label:**
```php
public static function getNavigationLabel(): string
{
    return __('navigation.users');
}
```

#### 27.3 Custom Navigation Item Icons

**Set Navigation Icon:**
```php
protected static ?string $navigationIcon = 'heroicon-o-users';
```

**Dynamic Icon:**
```php
public static function getNavigationIcon(): ?string
{
    return 'heroicon-o-users';
}
```

**Switch Icon When Navigation Item is Active:**
```php
protected static ?string $navigationActiveIcon = 'heroicon-o-users';
```

#### 27.4 Sort Navigation Items

**Set Navigation Sort:**
```php
protected static ?int $navigationSort = 1;
```

**Dynamic Sort:**
```php
public static function getNavigationSort(): ?int
{
    return 1;
}
```

#### 27.5 Add Badges to Navigation Items

**Add Badge:**
```php
protected static ?string $navigationBadge = 'New';
```

**Dynamic Badge:**
```php
public static function getNavigationBadge(): ?string
{
    return User::count();
}
```

**Badge Color:**
```php
protected static ?string $navigationBadgeColor = 'success';
```

**Badge Tooltip:**
```php
protected static ?string $navigationBadgeTooltip = 'New users this week';
```

#### 27.6 Group Navigation Items

**Use Navigation Group:**
```php
protected static ?string $navigationGroup = 'User Management';
```

**Dynamic Group:**
```php
public static function getNavigationGroup(): ?string
{
    return __('navigation.user_management');
}
```

**Group Other Items Under Navigation Group:**
```php
protected static ?string $navigationParentItem = 'settings';
```

**Custom Navigation Group:**
```php
use Filament\Navigation\NavigationGroup;

NavigationGroup::make('Website')
    ->icon('heroicon-o-globe-alt')
    ->collapsed();
```

**Register Navigation Groups Using Enums:**
```php
enum NavigationGroup: string
{
    case Website = 'website';
    case Settings = 'settings';
}

// Use in resource
protected static ?string $navigationGroup = NavigationGroup::Website->value;
```

#### 27.7 Collapsible Sidebar on Desktop

**Enable Desktop Collapse:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->sidebarCollapsibleOnDesktop();
}
```

**Navigation Groups in Desktop Collapsible Sidebar:**
```php
NavigationGroup::make('Website')
    ->collapsed();
```

#### 27.8 Register Custom Navigation Items

**Create Custom Navigation Item:**
```php
use Filament\Navigation\NavigationItem;

NavigationItem::make('Analytics')
    ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
    ->icon('heroicon-o-presentation-chart-line')
    ->group('Reports')
    ->sort(3);
```

**Dynamic Navigation Item:**
```php
NavigationItem::make('dashboard')
    ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
    ->url(fn (): string => Dashboard::getUrl())
    ->isActiveWhen(fn () => original_request()->routeIs('filament.admin.pages.dashboard'));
```

#### 27.9 Conditionally Hide Navigation Items

**Hide Navigation Items Based on Conditions:**
```php
use Filament\Navigation\NavigationItem;

NavigationItem::make('Analytics')
    ->visible(fn(): bool => auth()->user()->can('view-analytics'))
    // or
    ->hidden(fn(): bool => ! auth()->user()->can('view-analytics'));
```

#### 27.10 Disable Resource or Page Navigation Items

**Prevent Resources or Pages from Showing in Navigation:**
```php
protected static bool $shouldRegisterNavigation = false;
```

**Or Override Method:**
```php
public static function shouldRegisterNavigation(): bool
{
    return false;
}
```

#### 27.11 Use Top Navigation

**Configure Top Navigation:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->topNavigation();
}
```

#### 27.12 Custom Sidebar Width

**Set Sidebar Width:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->sidebarWidth('40rem');
}
```

**Custom Collapsed Icon Width:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->sidebarCollapsibleOnDesktop()
        ->collapsedSidebarWidth('9rem');
}
```

#### 27.13 Advanced Navigation Customization

**Build Custom Navigation:**
```php
use App\Filament\Pages\Settings;
use App\Filament\Resources\Users\UserResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use function Filament\Support\original_request;

public function panel(Panel $panel): Panel
{
    return $panel
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->items([
                NavigationItem::make('Dashboard')
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn (): string => Dashboard::getUrl()),
                ...UserResource::getNavigationItems(),
                ...Settings::getNavigationItems(),
            ]);
        });
}
```

**Register Custom Navigation Groups:**
```php
use App\Filament\Pages\HomePageSettings;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Pages\PageResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->groups([
                NavigationGroup::make('Website')
                    ->items([
                        ...PageResource::getNavigationItems(),
                        ...CategoryResource::getNavigationItems(),
                        ...HomePageSettings::getNavigationItems(),
                    ]),
            ]);
        });
}
```

#### 27.14 Disable Navigation

**Completely Disable Navigation:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->navigation(false);
}
```

#### 27.15 Disable Top Bar

**Disable Top Bar:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->topbar(false);
}
```

#### 27.16 Replace Sidebar and Top Bar Livewire Components

**Use Custom Components:**
```php
use App\Livewire\Sidebar;
use App\Livewire\Topbar;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->sidebarLivewireComponent(Sidebar::class)
        ->topbarLivewireComponent(Topbar::class);
}
```

#### 27.17 Disable Breadcrumbs

**Disable Breadcrumbs:**
```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->breadcrumbs(false);
}
```

#### 27.18 Reload Sidebar and Top Bar

**Dispatch Event from PHP:**
```php
// In any Livewire component
$this->dispatch('refresh-sidebar');

// In custom action
use Filament\Actions\Action;
use Livewire\Component;

Action::make('create')
    ->action(function (Component $livewire) {
        // ...
    
        $livewire->dispatch('refresh-sidebar');
    });
```

**Dispatch Event from JavaScript:**
```html
<!-- Using Alpine.js -->
<button x-on:click="$dispatch('refresh-sidebar')" type="button">
    Refresh Sidebar
</button>

<!-- Using native JavaScript -->
<script>
window.dispatchEvent(new CustomEvent('refresh-sidebar'));
</script>
```

#### 27.19 Navigation Best Practices

##### 27.19.1 Organization Principles
- **Logical Grouping**: Organize related functionality together
- **Hierarchy**: Use appropriate hierarchy to organize navigation
- **Consistency**: Maintain consistency in navigation labels and icons
- **Simplicity**: Avoid too many navigation items

##### 27.19.2 User Experience
- **Clear Labels**: Use descriptive navigation labels
- **Appropriate Icons**: Choose meaningful icons
- **Visual Feedback**: Provide appropriate active state indicators
- **Responsive Design**: Ensure usability on mobile devices

##### 27.19.3 Performance Considerations
- **Conditional Rendering**: Use conditional display appropriately
- **Permission Control**: Display navigation items based on user permissions
- **Caching Strategy**: Consider caching for navigation items

##### 27.19.4 Accessibility
- **Keyboard Navigation**: Ensure all navigation items are accessible via keyboard
- **Screen Readers**: Provide appropriate ARIA labels
- **Color Contrast**: Ensure sufficient color contrast
- **Focus Management**: Properly manage focus states

#### 27.20 Navigation Configuration Example

**Complete navigation configuration example:**
```php
use Filament\Panel;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

public function panel(Panel $panel): Panel
{
    return $panel
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder
                ->groups([
                    NavigationGroup::make('Content Management')
                        ->icon('heroicon-o-document-text')
                        ->items([
                            ...PageResource::getNavigationItems(),
                            ...PostResource::getNavigationItems(),
                            ...CategoryResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('User Management')
                        ->icon('heroicon-o-users')
                        ->items([
                            ...UserResource::getNavigationItems(),
                            ...RoleResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->items([
                            ...Settings::getNavigationItems(),
                        ]),
                ])
                ->items([
                    NavigationItem::make('Analytics')
                        ->url('https://analytics.example.com')
                        ->icon('heroicon-o-chart-bar')
                        ->group('Reports')
                        ->visible(fn(): bool => auth()->user()->can('view-analytics')),
                ]);
        })
        ->sidebarCollapsibleOnDesktop()
        ->sidebarWidth('20rem')
        ->collapsedSidebarWidth('5rem');
}
```

This navigation system provides powerful customization capabilities, allowing you to create intuitive and user-friendly admin interface navigation structures.

Remember, Filament is an active open-source project with regular updates and new features. It's recommended to check the official documentation regularly for the latest information.
