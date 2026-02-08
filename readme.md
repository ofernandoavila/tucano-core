# Tucano Core

Simple tools to help you create awesome wordpress plugins.

## Getting Started

Create new project:

```bash
$ composer create-project ofernandoavila/tucano <your-plugin-name>
```

or add on existing project:

```bash
$ composer require ofernandoavila/tucano-core
```

## Commands

This project contains a lot of useful tools to help you create some features.

Use the tucano tool to execute your commands:

```bash
$ ./bin/tucano init project-name
```

### Create

Use the tucano tool to execute your commands:

```bash
$ ./bin/tucano create:controller name
```

options:

- `controller`: Creates a new controller.
- `migration`: Creates a new migration.
- `model`: Creates a new model.
- `seed`: Creates a new seed.
- `shortcode`: Creates a new shortcode.

### Database

Use the tucano tool to execute your commands:

```bash
$ ./bin/tucano database:migrate
```

options:

- `migrate`: Run all migrations.
- `rollback`: Remove all migrations.

### Build

Use the tucano tool to execute your commands:

```bash
$ ./bin/tucano build:angular
```

options:

- `angular`: Build the web components project.
