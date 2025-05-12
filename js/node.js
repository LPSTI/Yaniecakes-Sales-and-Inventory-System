const express = require('express');
const Pusher = require('pusher');
const cors = require('cors');
const app = express();

const pusher = new Pusher({
  appId: '1974101',
  key: 'eac4bab75c7b0b2e41bd',
  secret: 'c11ca1a4e4697119754b',
  cluster: 'ap1',
  useTLS: true
});

app.use(cors());
app.use(express.json());

app.post('/trigger-event', (req, res) => {
  const data = req.body;
  pusher.trigger('poop', 'newpoop', data);
  res.send({ success: true });
});

app.listen(3000, () => console.log('Server running on port 3000'));
