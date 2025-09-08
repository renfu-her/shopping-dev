# Filament v4 Complete Guide - Part 3: Form System

## Form System

### Overview
**v4 Form System Features:**
- Responsive forms based on Livewire 3
- Supports real-time validation and error handling
- Rich field types and validation options
- Highly customizable styles and behavior
- Supports conditional fields and dynamic forms

## Basic Field Components

### Text Input
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

### Select
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

### Checkbox
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

### Toggle
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

### Radio
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

### Date Time Picker
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

### File Upload
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

## Advanced Field Components

### Rich Editor
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

### Markdown Editor
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

### Repeater
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

### Builder
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

### Tags Input
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

### Textarea
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

### Key Value
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

### Color Picker
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

### Toggle Buttons
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

### Slider
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

### Code Editor
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

### Hidden
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

## Validation System
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

## Custom Fields
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

## Best Practices

### Performance Optimization
- Use appropriate database queries
- Avoid N+1 query problems
- Use conditional rendering reasonably
- Cache complex calculations

### User Experience
- Provide clear labels and descriptions
- Use appropriate icons and colors
- Ensure responsive design
- Provide useful validation messages

### Security
- Implement appropriate permission checks
- Validate all user input
- Protect sensitive data
- Use CSRF protection

### Code Organization
- Use appropriate namespaces
- Follow PSR standards
- Keep code clean
- Add appropriate comments

---

**Previous:** [Part 2: Table System](FILAMENT-02-TABLES.md)  
**Next:** [Part 4: Infolists](FILAMENT-04-INFOLISTS.md)
