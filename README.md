# Spreadsheet Translator - OneDrive Provider

[![Tests](https://github.com/samuelvi/spreadsheet-translator-provider-onedrive/workflows/Tests/badge.svg)](https://github.com/samuelvi/spreadsheet-translator-provider-onedrive/actions)
[![Latest Stable Version](https://poser.pugx.org/samuelvi/spreadsheet-translator-provider-onedrive/v/stable)](https://packagist.org/packages/samuelvi/spreadsheet-translator-provider-onedrive)
[![License](https://poser.pugx.org/samuelvi/spreadsheet-translator-provider-onedrive/license)](https://packagist.org/packages/samuelvi/spreadsheet-translator-provider-onedrive)

This package provides a OneDrive provider for the Spreadsheet Translator project. It fetches shared spreadsheet documents from Microsoft OneDrive **without requiring authentication**.

## Features

- Fetch publicly shared spreadsheets from OneDrive
- No authentication required (works with public share links)
- Automatic conversion from embed URLs to download URLs
- Integrated with the Spreadsheet Translator ecosystem

## Requirements

- PHP >= 8.4
- [Spreadsheet Translator Core](https://github.com/samuelvi/spreadsheet-translator-core) ^8.0

## Installation

Install via Composer:

```bash
composer require samuelvi/spreadsheet-translator-provider-onedrive
```

## Usage

```php
use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Provider\OneDrive\OneDriveProvider;

$configuration = new Configuration([
    'source_resource' => 'https://onedrive.live.com/embed?id=YOUR_FILE_ID',
    'temp_local_source_file' => '/tmp/spreadsheet.xlsx',
    'format' => 'xlsx',
]);

$provider = new OneDriveProvider($configuration);
$resource = $provider->handleSourceResource();

// The spreadsheet is now downloaded to the temporary file
echo $resource->getValue(); // /tmp/spreadsheet.xlsx
```

## Development

### Available Make Commands

```bash
make help           # Show available commands
make install        # Install dependencies
make update         # Update dependencies
make test           # Run tests
make test-coverage  # Run tests with HTML coverage report
make rector         # Run Rector to refactor code
make rector-dry     # Preview Rector changes without applying
make jack           # Check for outdated dependencies
make quality        # Run quality checks (rector + tests)
make check          # Check code without making changes
make clean          # Clean generated files
make ci             # Run CI pipeline locally
```

### Running Tests

```bash
# Run all tests
make test

# Or using Composer
composer test

# Run with coverage
make test-coverage
```

### Code Quality

This project uses [Rector](https://github.com/rectorphp/rector) for automated code refactoring:

```bash
# Preview changes
make rector-dry

# Apply changes
make rector
```

## Related Packages

- [Spreadsheet Translator Core](https://github.com/samuelvi/spreadsheet-translator-core)
- [Spreadsheet Translator Symfony Bundle](https://github.com/samuelvi/spreadsheet-translator-symfony-bundle)

## Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and code quality checks (`make check`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to your branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

Please ensure:
- All tests pass
- Code follows PHP 8.4 standards
- New features include tests

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Author

Samuel Vicent - <samuelvicent@gmail.com>