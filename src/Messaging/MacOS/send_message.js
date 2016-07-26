ObjC.import('stdlib')

class Keyboard
{
  constructor () {
    this.system = Application('System Events');

    this.modifiers = {
      "⌘": "command down", "^": "control down",
      "⌥": "option down",  "⇧": "shift down",
    }

    this.keycodes = {
      "→": 124, "←": 123,
      "↑": 126, "↓": 125,
      "⏎": 36,
    }
  }

  press (keystrokes) {
    var modifiers = [];

    while (keystrokes.length > 1) {
      var key = keystrokes[0];

      if (! this.modifiers.hasOwnProperty(key)) {
        break;
      }

      modifiers.push(this.modifiers[key]);
      keystrokes = keystrokes.slice(1);
    }

    if (this.keycodes.hasOwnProperty(keystrokes)) {
      return this.system.keyCode(this.keycodes[keystrokes], { using: modifiers });
    }

    this.system.keystroke(keystrokes, { using: modifiers });

    delay(0.4);
  }

  send (keystrokes) {
    this.press(keystrokes);

    this.press('⏎');
  }
}

class Skype
{
  constructor () {
    this.skype    = Application('Skype');
    this.keyboard = new Keyboard;
  }

  focus () {
    this.skype.activate();

    delay(0.2);
  }

  open_conversation_with (conversation) {
    this.keyboard.press('⌘⌥f'); // Open the search user bar

    this.keyboard.send(conversation);
  }

  send (message) {
    this.keyboard.send(message);
  }
}

function run(argv) {
  var message = argv[0];
  var skype = new Skype;

  skype.focus();
  skype.open_conversation_with($.getenv('SKYPE_CONVERSATION'));
  skype.send(message);
}
