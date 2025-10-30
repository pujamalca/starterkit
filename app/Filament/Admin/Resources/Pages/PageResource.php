<?php

namespace App\Filament\Admin\Resources\Pages;

use App\Filament\Admin\Resources\Pages\PageResource\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\PageResource\Pages\ListPages;
use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationLabel = 'Halaman Statis';

    protected static UnitEnum|string|null $navigationGroup = 'Konten';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Page Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Konten')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(200)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                \Filament\Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(200)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Slug digunakan pada URL. Pastikan unik.'),
                            ]),
                            \Filament\Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Dipublikasikan',
                                    'scheduled' => 'Terjadwal',
                                ])
                                ->default('draft')
                                ->required(),

                            // Tabs untuk Content: Visual Editor dan HTML Source
                            Tabs::make('content_tabs')
                                ->columnSpanFull()
                                ->contained(false)
                                ->tabs([
                                    Tab::make('Editor Visual')
                                        ->icon('heroicon-o-document-text')
                                        ->schema([
                                            \Filament\Forms\Components\RichEditor::make('content')
                                                ->label('Konten')
                                                ->columnSpanFull()
                                                ->fileAttachmentsDisk('public')
                                                ->fileAttachmentsDirectory('pages/editor')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (?string $state, callable $set) => $set('content_html', $state))
                                                ->afterStateHydrated(fn ($state, callable $set) => $set('content_html', $state))
                                                ->extraInputAttributes([
                                                    'style' => 'min-height: 30rem; max-height: 60vh; overflow-y: auto;'
                                                ])
                                                ->toolbarButtons([
                                                    'attachFiles',
                                                    'blockquote',
                                                    'bold',
                                                    'bulletList',
                                                    'codeBlock',
                                                    'h2',
                                                    'h3',
                                                    'italic',
                                                    'link',
                                                    'orderedList',
                                                    'redo',
                                                    'strike',
                                                    'table',
                                                    'underline',
                                                    'undo',
                                                ]),
                                        ]),
                                    Tab::make('HTML Source')
                                        ->icon('heroicon-o-code-bracket')
                                        ->schema([
                                            \Filament\Forms\Components\CodeEditor::make('content_html')
                                                ->label('HTML Code')
                                                ->language(\Filament\Forms\Components\CodeEditor\Enums\Language::Html)
                                                ->columnSpanFull()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (?string $state, callable $set) => $set('content', $state))
                                                ->afterStateHydrated(function ($state, callable $set) {
                                                    // Format HTML agar rapi dengan line breaks dan indentasi
                                                    if ($state) {
                                                        $formatted = self::formatHtml($state);
                                                        $set('content_html', $formatted);
                                                    }
                                                })
                                                ->dehydrated(false)
                                                ->extraAttributes([
                                                    'style' => 'white-space: pre-wrap !important; word-break: break-word !important; overflow-x: hidden !important;',
                                                    'class' => 'code-editor-wrapped',
                                                ])
                                                ->helperText('Edit HTML secara langsung dengan syntax highlighting. Code akan wrap otomatis tanpa scroll horizontal.'),
                                        ]),
                                ]),
                        ]),
                    Tab::make('Publikasi')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText('Tanggal halaman dipublikasikan.'),
                                \Filament\Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('Jadwalkan Publikasi')
                                    ->seconds(false)
                                    ->native(false)
                                    ->helperText('Isi jika status diset menjadi Terjadwal.'),
                            ]),
                            Section::make('Tampilan Menu')
                                ->schema([
                                    Grid::make(3)->schema([
                                        \Filament\Forms\Components\Toggle::make('show_in_header')
                                            ->label('Tampilkan di Header')
                                            ->helperText('Halaman akan muncul di menu navigasi header.'),
                                        \Filament\Forms\Components\Toggle::make('show_in_footer')
                                            ->label('Tampilkan di Footer')
                                            ->helperText('Halaman akan muncul di menu footer.'),
                                        \Filament\Forms\Components\TextInput::make('menu_order')
                                            ->label('Urutan Menu')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Urutan tampilan menu (semakin kecil, semakin awal).'),
                                    ]),
                                ])
                                ->collapsed(),
                        ]),
                    Tab::make('SEO & Metadata')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('seo_title')
                                ->label('Judul SEO')
                                ->maxLength(60)
                                ->helperText('Maksimal 60 karakter untuk SEO optimal.'),
                            \Filament\Forms\Components\Textarea::make('seo_description')
                                ->label('Deskripsi SEO')
                                ->rows(3)
                                ->maxLength(160)
                                ->helperText('Maksimal 160 karakter untuk SEO optimal.'),
                            \Filament\Forms\Components\TagsInput::make('seo_keywords')
                                ->label('Kata Kunci SEO')
                                ->separator(',')
                                ->helperText('Tekan Enter atau koma untuk menambahkan tag. Contoh: halaman utama, tentang kami, kontak'),
                            Grid::make(2)->schema([
                                \Filament\Forms\Components\TextInput::make('canonical_url')
                                    ->label('Canonical URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->helperText('URL kanonik untuk halaman ini.'),
                                \Filament\Forms\Components\TextInput::make('og_image')
                                    ->label('URL Gambar Open Graph')
                                    ->url()
                                    ->maxLength(255)
                                    ->helperText('Gambar untuk social media sharing.'),
                            ]),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'primary' => 'scheduled',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasikan',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Dipublikasikan',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Page $record) => route('pages.preview', $record))
                    ->openUrlInNewTab(),
                \Filament\Actions\Action::make('publish')
                    ->label('Publikasikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (Page $record) => $record->status === 'published')
                    ->requiresConfirmation()
                    ->action(function (Page $record): void {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                            'scheduled_at' => null,
                        ]);
                    }),
                \Filament\Actions\Action::make('draft')
                    ->label('Jadikan Draft')
                    ->icon('heroicon-o-document')
                    ->color('warning')
                    ->hidden(fn (Page $record) => $record->status === 'draft')
                    ->requiresConfirmation()
                    ->action(function (Page $record): void {
                        $record->update([
                            'status' => 'draft',
                        ]);
                    }),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('author');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage-pages') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage-pages') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('manage-pages') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('manage-pages') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('manage-pages') ?? false;
    }

    /**
     * Format HTML untuk ditampilkan rapi di CodeEditor
     */
    protected static function formatHtml(string $html): string
    {
        if (empty($html)) {
            return $html;
        }

        // Step 1: Add line breaks between tags
        $html = preg_replace('/>\s*</', ">\n<", $html);

        // Step 2: Break long lines with many attributes
        $html = preg_replace_callback('/<([a-z][a-z0-9]*)\s+([^>]+)>/i', function ($matches) {
            $tag = $matches[1];
            $attrs = $matches[2];

            // Jika attributes terlalu panjang (lebih dari 80 karakter), pecah per attribute
            if (strlen($attrs) > 80) {
                // Split attributes
                preg_match_all('/(\w+(?:-\w+)*)\s*=\s*["\']([^"\']*)["\']/', $attrs, $attrMatches, PREG_SET_ORDER);

                if (count($attrMatches) > 1) {
                    $formattedAttrs = "\n";
                    foreach ($attrMatches as $attr) {
                        $formattedAttrs .= "    {$attr[1]}=\"{$attr[2]}\"\n";
                    }
                    return "<{$tag}{$formattedAttrs}>";
                }
            }

            return $matches[0];
        }, $html);

        // Step 3: Split by lines and indent
        $lines = explode("\n", $html);
        $formatted = [];
        $indent = 0;
        $maxLineLength = 100; // Maximum character per line

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Decrease indent for closing tags
            if (preg_match('/^<\/[^>]+>/', $line)) {
                $indent = max(0, $indent - 1);
            }

            // Check if line is an attribute line
            $isAttribute = preg_match('/^\w+(-\w+)*\s*=/', $line);

            if ($isAttribute) {
                // Attribute lines get extra indent
                $formatted[] = str_repeat('    ', $indent + 1) . $line;
            } else {
                // Regular lines
                $indentedLine = str_repeat('    ', $indent) . $line;

                // If line is too long and has text content, try to break it
                if (strlen($indentedLine) > $maxLineLength && preg_match('/>([^<]+)</', $line, $textMatch)) {
                    $text = $textMatch[1];
                    if (strlen($text) > 60) {
                        // Break long text content
                        $parts = explode(' ', $text);
                        $currentLine = '';
                        $textLines = [];

                        foreach ($parts as $word) {
                            if (strlen($currentLine . ' ' . $word) > 60) {
                                if ($currentLine) {
                                    $textLines[] = $currentLine;
                                }
                                $currentLine = $word;
                            } else {
                                $currentLine .= ($currentLine ? ' ' : '') . $word;
                            }
                        }
                        if ($currentLine) {
                            $textLines[] = $currentLine;
                        }

                        // Reconstruct the line with broken text
                        $line = preg_replace('/>([^<]+)</', '>' . implode("\n" . str_repeat('    ', $indent + 1), $textLines) . '<', $line);
                        $formatted[] = str_repeat('    ', $indent) . $line;
                    } else {
                        $formatted[] = $indentedLine;
                    }
                } else {
                    $formatted[] = $indentedLine;
                }
            }

            // Increase indent for opening tags (but not self-closing)
            if (!$isAttribute && preg_match('/<[^\/!][^>]*[^\/]>$/', $line) && !preg_match('/<[^>]+\/>$/', $line)) {
                // Check if tag closes on same line
                if (!preg_match('/<([a-z][a-z0-9]*)[^>]*>.*<\/\1>/i', $line)) {
                    $indent++;
                }
            }
        }

        return implode("\n", $formatted);
    }
}
