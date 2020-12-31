# Frequent Files

Files frequently used for my project. Based on basic models actions.

# Installation

Run command `php artisan generate:module Users` (Convention is to use plural for model with first letter uppercase)

If you want to generate files for 'User' model, run command package will insert files in a directory which name is
defined in env APP_NAME var. following files will generate an interface UserRepository, class EloquentRepository (which
implement UserRepository), classes UserService and SearchService as a trait ModelTrait (used by EloquentRepository and
SearchService).

For Laravel version 5.4 and above you need to register `\KovaLiman\FrequentlyFiles\FrequentFilesServiceProvider::class`
in config/app.php file.
