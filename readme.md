# Work Scripts

This is a small library to make everything a tad bit easier at work.

The company I work at heavily depends on Basecamp in logging our work hours. So I came up with a small cli script to just use the git commits to log the hours.

## Installation

You can install the scripts with composer

```
composer global require jaggy/work-scripts
```

## Usage

Before I get into the nitty gritty parts, let's just show it off to see how it works.

```bash
$ work time:log

Yow! (￣^￣)ゞ
Which project would you like to add an entry to? [It might take a while to fetch the project list]
1: xxxxxxxxxxxxxxxxxxxxxxx
2: xxxxxxxxxxxxxxxxxxxxxxx
3: xxxxxxxxxxxxxxxxxxxxxxx
4: xxxxxxxxxxxxxxxxxxxxxxx
Enter the id of the project: [Leave blank to cancel]: 2
Log description []: Update the project readme.
How many hours did this task take? [2.25 hours remaining]]: 2
Project ID: xxxxxxxxxxx
Log Description: Update the project readme
Rendered Hours: 0.2
Is the provided data correct? [n] y
Sending the data to basecamp...
Time is now logged! You're good to go! (╯°□°）╯︵ ┻━┻
```

Also, you can just force the parameters. This is how the git hook makes everything much easier.

```
work time:log --project=<PROJECT_ID> --description="Log Entry" --hours=1.0
```

More or less, that's basically how it works.

## Configuration

### Setting up Basecamp

`~/.workrc`

To make the work scripts work, you need to register your environment in your home directory `~/.workrc`.

```
BASECAMP_URL=null
BASECAMP_USERNAME=null
BASECAMP_PASSWORD=null
```

### Initializing a project

To initialize your project, you just need to run `work init`. From there, it'll do the following:
- Register `.work` in your `.gitignore`
- Create a `.work` file in your project root.
- Ask which basecamp project you want to assiciate with that project and register it to your work config.
- Ask if you want to write a prefix for your logs.
- Register a `post-commit` git hook to your project.

## Commands

| Command          | Description                                          |
|------------------|------------------------------------------------------|
| `init`           | Initialize the current directory as a work project.  |
| `scrum`          | Start a SCRUM session and log to basecamp.           |
| `time:log`       | Log a time entry to basecamp.                        |
| `time:remaining` | Fetch the remaining hours to render from basecamp.   |

## Todo
- [ ] Add caching to avoid repeated requests to basecamp.
- [ ] Add an offline handler to send the basecamp log once an internet connection is detected.
