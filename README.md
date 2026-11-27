# ArgFrame

## Table of Contents
- [Tutorial video](#tutorial-video)
- [Overview](#overview)
- [Getting started](#getting-started)
- [Testing](#testing)
- [Production deployment](#production-deployment-including-shared-hosting)
- [Citing this project](#citing-this-project)
- [Contact me](#contact)
- [References](#references)

<a name="tutorial-video"></a>
## Tutorial Video
There is a video demonstrating the basic usage of the framework https://youtu.be/8gD3BQENxu8

<a name="overview"></a>
## Overview
This argumentation framework has been proposed as a tool to perform automated reasoning with numerical data. It is able to use boolean logic for the creation of if-then rules and attacking rules. In turn, these rules can be activated by data, have their attacks solved, and finally aggregated in different fashions in order to produce a prediction (a number). This process works in the following order:

1. feature set creation;

![Screenshot from 2022-06-28 11-45-04](https://user-images.githubusercontent.com/55241866/176161779-e752748c-ae3b-4ea9-a12c-d44b6323e60c.png)

2. creation of rules and attacks employing the features created in (1), which results in an argumentation graph;

![Screenshot from 2022-06-28 11-56-36](https://user-images.githubusercontent.com/55241866/176162633-9ee19e22-0d9e-44f6-a36c-b38c95d0b58f.png)

![Screenshot from 2022-06-28 11-58-37](https://user-images.githubusercontent.com/55241866/176162992-e4e337ef-8177-45d2-91ad-00040afa7f13.png)

3. instantiation of graph(s) from (2) with numerical data and case-by-case analysis with computation of predictions;

![Screenshot from 2022-06-28 12-01-44](https://user-images.githubusercontent.com/55241866/176163525-4749dff7-1b93-48fb-8f41-13acb855a3a0.png)

4. export all results to a csv file for any number of combinations of agumentation semantics and accrual strategies.

![Screenshot from 2022-06-28 12-04-07](https://user-images.githubusercontent.com/55241866/176163881-c634b204-27d7-4b5b-a3ba-f40c60f2225c.png)

### Running example
A running example can be seen here https://lucasrizzo.com/framework/index.php

<a name="getting-started"></a>
## Getting started

### Requirements

- Docker Engine or Docker Desktop with Docker Compose v2 (recommended for local development).
- PHP 8.4, Composer 2 and MySQL 8.0 for a manual installation.
- PHP extensions: PDO with MySQL, ZIP, GD, mbstring, DOM, SimpleXML, XML, XMLReader and XMLWriter, plus the platform requirements in `www/composer.lock`.

The Docker image supplies the PHP runtime, Composer and these extensions. PhpSpreadsheet replaces the bundled PHPExcel library; PHPUnit is a development dependency.

### Local installation with Docker

From a terminal:

```bash
git clone https://github.com/LucasRizzo/argframe.git
cd argframe
docker compose up -d --build
docker compose exec www composer install
```

Open [ArgFrame](http://localhost:8001/index.php/login). The Guest login provides access to example feature sets and graphs; example datasets can be downloaded from the Compute graph page.

[phpMyAdmin](http://localhost:8000/) is available locally. The default development database is `arg-db`, with user `admin` and password `admin`. These defaults are for local development only. All published Docker ports are bound to localhost.

To stop the containers while retaining the database:

```bash
docker compose down
```

Do not use `docker compose down -v` unless you intend to remove the database volume and its contents.

### Local database configuration

For a new Docker installation, optionally create a root-level `.env` file before starting the services:

```dotenv
ARGFRAME_DB_PASSWORD=replace_with_a_strong_password
ARGFRAME_DB_ROOT_PASSWORD=replace_with_a_different_strong_password
```

For an existing persistent MySQL volume, changing these values does not change the passwords already stored in MySQL. Change the database accounts explicitly before updating the application credentials. The `.env` file is ignored by Git.

### Upgrading an existing installation

1. Back up the application files and the database, and stop writes during the upgrade.
2. Update the source files and rebuild/recreate the Docker services:

   ```bash
   git pull
   docker compose up -d --build
   docker compose exec www composer install
   ```

3. On the existing database, run `migrations/001_expand_password_hash.sql` once before using the updated authentication:

   ```sql
   ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL;
   ```

   This preserves existing user accounts. The SQL dump already contains the expanded column for new installations. Do not reimport the initial dump into an existing database.

4. Sign in again and validate the application.

Existing MD5 password hashes are upgraded to PHP's `password_hash()` format after a successful login. Do not create new MD5 hashes. Back up the database before migration: older versions cannot authenticate accounts whose hashes have been upgraded.

### Creating registered users

Generate a password hash with PHP's `password_hash($password, PASSWORD_DEFAULT)`. For example, from Git Bash or another Bash shell:

```bash
read -r -s -p "New password: " password
printf '\n'
printf '%s' "$password" | docker compose exec -T www php -r 'echo password_hash(stream_get_contents(STDIN), PASSWORD_DEFAULT), PHP_EOL;'
unset password
```

In phpMyAdmin, insert the user's email and the generated hash into the `users` table. Feature-set access is controlled by the `user_featureset` table. Feature-set names must be unique across all accounts because the current schema references feature sets by name. Guest users share the Guest account's feature sets; temporary dataset computation results are isolated by session.

## Testing

Run the test suite inside the container:

```bash
docker compose exec www composer test
docker compose exec www composer check-platform-reqs
docker compose exec www composer audit
```

The PHPUnit suite includes deterministic regression cases for argumentation semantics and categoriser scores, together with migrated legacy DungAF tests. Composer audit covers Composer-managed dependencies, not the bundled legacy Slim or JavaScript libraries.

Before a release, also test Guest and registered login/logout, feature-set and graph CRUD, JSON import/export, individual semantics, CSV import, inference/statistics export and multi-chunk dataset export. Verify that unauthorised feature-set requests fail, missing CSRF tokens are rejected, and user-defined names display as text.

If an application error occurs, inspect the server logs rather than enabling public diagnostics:

```bash
docker compose logs --tail=100 www
```

## Production deployment (including shared hosting)

The Docker setup is intended for local development. A production deployment needs its own database, credentials and HTTPS configuration.

1. Back up the existing site and database. Prepare a staging copy and select PHP 8.4 in the hosting control panel.
2. Enable the required PHP extensions. Run Composer from the `www` directory using PHP 8.4:

   ```bash
   composer install --no-dev --optimize-autoloader
   composer check-platform-reqs --no-dev
   ```

   If Composer is unavailable on the host, install dependencies in a compatible PHP 8.4 environment and upload the resulting `vendor/` directory along with the application. Do not upload development dependencies.
3. Deploy the contents of `www/` to the site's application directory, including `.htaccess` and `.user.ini`. Keep the repository metadata, database dumps and migration scripts outside the public web root.
4. Copy `www/conf/config.local.example.php` to `www/conf/config.local.php` on the deployment and enter the hosting database settings. Alternatively set the corresponding environment variables (`ARGFRAME_DB_HOST`, `ARGFRAME_DB_PORT`, `ARGFRAME_DB_USER`, `ARGFRAME_DB_PASSWORD`, `ARGFRAME_DB_NAME`). Environment variables take precedence. Never commit the local configuration file.
5. Configure the deployment URL and asset paths in `www/conf/config.inc.php`. The current defaults are for Docker at `http://localhost:8001/`; a deployment under a subdirectory such as `/framework/` must adjust `ADDRESS_PREFIX`, `ADDRESS_CALL`, `ABSOLUTE_CALL` and the root-relative asset constants as needed. Use HTTPS and the actual deployment path, not localhost.
6. Apply the password-column migration to the production database before the first upgraded login. For a fresh database, import the initial dump into an empty database instead.
7. Verify that PHP error display is off and logging is enabled. Confirm the hosting server honours the access restrictions in `.htaccess` and appropriate PHP settings in `.user.ini`. Direct requests to `conf/`, `db/`, `tests/`, `vendor/`, `composer.json`, `composer.lock` and `teste.php` must be denied while public JavaScript and CSS remain accessible.
8. Validate the complete application in staging, including CSV exports, before replacing the live installation.

Do not expose phpMyAdmin, the MySQL port or default development credentials on the public internet. Rotate any production credentials previously committed to Git. The legacy workload-specific XLS export has not been validated end to end; the current dataset interface exports CSV files. A migration to modern Slim and JavaScript dependencies remains a separate maintenance task.

<a name="citing-this-project"></a>
## Citing this project

If you use this project in your research, please cite the following paper:

- Rizzo, L. (2023). ArgFrame: A multi-layer, web, argument-based framework for quantitative reasoning. *Software Impacts*, 17, 100547.

Or with bibtex:
```bibtex
@article{RIZZO2023100547,
title = {ArgFrame: A multi-layer, web, argument-based framework for quantitative reasoning},
journal = {Software Impacts},
volume = {17},
pages = {100547},
year = {2023},
issn = {2665-9638},
doi = {https://doi.org/10.1016/j.simpa.2023.100547},
url = {https://www.sciencedirect.com/science/article/pii/S2665963823000842},
author = {Lucas Rizzo},
keywords = {Defeasible argumentation, Automated reasoning, Knowledge-based systems, Dung semantics, Data analysis}
}
```
<a name="contact"></a>
## Contact me

Contact me at lucasmrizzo@gmail.com or lucas.rizzo@tudublin.ie

<a name="references"></a>
## References

The following papers have used this framework for experimentation.

<a id="1">[1]</a> 
**Rizzo, L.**, Verda, D., Berretta, S., Longo, L. (2024) A Novel Integration of Data-Driven Rule Generation and Computational Argumentation for Enhanced Explainable AI Mach. Learn. Knowl. Extr., 2024, 6(3), 2049-2073

<a id="1">[2]</a> 
**Rizzo, L.** and Longo, L. (2022) Examining the modelling capabilities of defeasible argumentation and non-monotonic fuzzy reasoning Information Fusion, 89, 537-566

<a id="2">[3]</a> 
Longo, L. and **Rizzo, L.** (2021) Examining the modelling capabilities of defeasible argumentation and non-monotonic fuzzy reasoning Knowledge-Based Systems, p. (in press)

<a id="3">[4]</a> 
**Rizzo, L.** (2020) Evaluating the Impact of Defeasible Argumentation as a Modelling Technique for Reasoning under Uncertainty. Doctoral Thesis, Technological University Dublin.

<a id="4">[5]</a>
**Rizzo, L.** and Longo, L. (2020) An empirical evaluation of the inferential capacity of defeasible argumentation, non-monotonic fuzzy reasoning and expert systems Expert Systems with Applications 147, p. (in press)

<a id="5">[6]</a>
**Rizzo, L.**, Dondio P., Longo L. (2020) Exploring the potential of defeasible argumentation for quantitative inferences in real-world contexts: An assessment of computational trust intelligence in: Proceedings for the 28th AIAI Irish Conference on Artificial Intelligence and Cognitive Science pp. 25-36

<a id="6">[7]</a>
**Rizzo, L.** and Longo, L. (2019) Inferential models of mental workload with defeasible argumentation and non-monotonic fuzzy reasoning: a comparative study in: 2nd Workshop on Advances In Argumentation In Artificial Intelligence pp. 11–26

<a id="7">[8]</a>
**Rizzo, L.** and Longo, L. (2018) A qualitative investigation of the degree of explainability of defeasible argumentation and non-monotonic fuzzy reasoning in: 26th AIAI Irish Conference on Artificial Intelligence and Cognitive Science pp. 138–149

<a id="8">[9]</a>
**Rizzo, L.**, Majnaric, L. and Longo, L. (2018) A comparative study of defeasible argumentation and non-monotonic fuzzy reasoning for elderly survival prediction using biomarkers in: AI*IA 2018 – Advances in Artificial Intelligence, (Eds.) C. Ghidini, B. Magnini, A. Passerini and P. Traverso pp. 197–209 Springer International Publishing, Cham

<a id="9">[10]</a>
**Rizzo, L.**, Majnaric, L., Dondio, P. and Longo, L. (2018) An investigation of argumentation theory for the prediction of survival in elderly using biomarkers in: Artificial Intelligence Applications and Innovations, (Eds.) L. Iliadis, I. Maglogiannis and V. Plagianakos pp. 385–397 Springer International Publishing, Cham

<a id="10">[11]</a>
**Rizzo, L.** and Longo, L. (2017) Representing and inferring mental workload via defeasible reasoning: a comparison with the nasa task load index and the workload profile in: 1st Workshop on Advances In Argumentation In Artificial Intelligence, Bari, Italy pp. 126–140
