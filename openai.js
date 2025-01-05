console.log('OpenAI')   


// Example usage
const openai = new OpenAI({
    apiKey: 'sk-7wnsE3WpH1WOG4MG_xKR9wa0ae-1lmj-aXAJlEQxSYT3BlbkFJt4mmyvjcOk5sKPjaAEdLyYwgxjyYbwNvNOOLIlxcIA', // Replace with your actual API key
});

// Example function to get a response from the API
async function getChatResponse(prompt) {
    const response = await openai.chat.completions.create({
        model: 'gpt-3.5-turbo',
        messages: [{ role: 'user', content: prompt }],
    });
    console.log(response.choices[0].message.content);
}

// Usage
getChatResponse("Tell me a joke");
