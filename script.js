
  function startVoiceNote() {
      const recognition = new webkitSpeechRecognition() || new SpeechRecognition();
      recognition.lang = 'en-US';

      recognition.onstart = () => {
          document.getElementById('voiceNoteStatus').innerText = 'Voice recognition started...';
      };

      recognition.onresult = (event) => {
          const transcript = event.results[0][0].transcript;
          document.getElementById('description').value = transcript;
          document.getElementById('voiceNoteStatus').innerText = 'Voice recognition complete.';
      };

      recognition.onerror = (event) => {
          document.getElementById('voiceNoteStatus').innerText = 'Error during voice recognition. Please try again.';
      };

      recognition.onend = () => {
          document.getElementById('voiceNoteStatus').innerText = 'Voice recognition ended.';
      };

      recognition.start();
  }
