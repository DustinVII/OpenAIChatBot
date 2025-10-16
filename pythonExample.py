from openai import OpenAI
from openai import APIError, APIConnectionError, RateLimitError, AuthenticationError

#Create a new secret API key at https://platform.openai.com/settings/organization/api-keys

client = OpenAI(api_key="xxx")

def chat_with_gpt(prompt):
    try:
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[{"role": "user", "content": prompt}]
        )
        return response.choices[0].message.content.strip()

    except RateLimitError:
        return "You've reached your usage limit or have no remaining credits. Please check your OpenAI billing settings."

    except AuthenticationError:
        return "Invalid API key. Please verify your key in the script."

    except APIConnectionError:
        return "Network error — could not connect to the OpenAI server. Please check your internet connection."

    except APIError as e:
        return f"An unexpected API error occurred: {e}"

if __name__ == "__main__":
    print("ChatGPT is ready! Type 'exit' or 'quit' to stop.\n")
    while True:
        user_input = input("You: ")
        if user_input.lower() in ["quit", "exit", "bye"]:
            print("Goodbye!")
            break

        response = chat_with_gpt(user_input)
        print("Chatbot:", response)