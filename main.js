const Discord = require('discord.js');
const axios = require('axios');

const channelId = 'XXXX'; //Идентификатор целевого канала Target channel ID
const rconUrl = 'http://localhost/rcon.php'; //URL для отправки RCON-запроса URL to send RCON request
const client = new Discord.Client({
    intents: [
        Discord.GatewayIntentBits.Guilds,
        Discord.GatewayIntentBits.GuildMembers,
        Discord.GatewayIntentBits.GuildMessages,
        Discord.GatewayIntentBits.MessageContent
    ]
});
client.on('ready', () => {
  console.log(`Бот ${client.user.tag} успешно запущен!`);
});

client.on('messageCreate', async (message) => {
  // Проверяем, что сообщение пришло из нужного канала и не от другого бота
  // Check if the message came from the right channel and not from another bot
  if (message.channel.id === channelId && !message.author.bot) {
    const authorName = message.author.username;
    const messageText = message.content;
	console.log(authorName+","+messageText);
    // Отправляем POST-запрос на localhost/rcon.php с данными отправителя и текстом сообщения
    // Send a POST request to localhost/rcon.php with sender data and message text
    try {
      const response = await axios.get(rconUrl, {
        params: {
          author: authorName,
          message: messageText,
        },
      });
      console.log('RCON-запрос успешно выполнен:', response.data);
    } catch (error) {
      console.error('Ошибка при выполнении RCON-запроса:', error);
    }
  }
});

// Вставьте здесь ваш токен Discord-бота
// Paste your Discord bot token here
const token = 'XXX';

client.login(token);
