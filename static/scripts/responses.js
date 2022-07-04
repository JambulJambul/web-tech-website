function getBotResponse(input) {
// Simple responses
    if (input == "Hi there!") {
        return "Hello there!";
    } 
    
    else if (input == "How are you?") {
        return "Fine, hbu?";
    } 
    
    else if (input == "Bye!") {
        return "See ya later!";
    }
    
    else if (input == "What's your name?") {
        return "My name is Elon Musk!";
    }   

    else if (input == "What's your favorite food?") {
        return "I like pizza!";
    }   

    else if (input == "What's your favorite color?") {
        return "I like blue!";
    }

    else if (input == "What's your favorite animal?") {
        return "I like dogs!";
    }          

    else {
        return "Please use the determine message to continue.";
    }
}