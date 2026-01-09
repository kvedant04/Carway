import express from "express";
import fetch from "node-fetch";
import bodyParser from "body-parser";
import cors from "cors";

const app = express();
app.use(cors());
app.use(bodyParser.json());

const GEMINI_API_KEY = "AIzaSyBWcssT_u7oqTFccaCY6ne_5JEfxGfXsDw";
const OPENAI_API_KEY = "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";

// Helper: delay
function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Helper: Call Gemini API
async function callGemini(model, message) {
    const response = await fetch(
        `https://generativelanguage.googleapis.com/v1/models/${model}:generateContent?key=${GEMINI_API_KEY}`,
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                contents: [
                    {
                        parts: [
                            { text: "You are CarWay AI, a friendly and knowledgeable assistant about cars, car buying, maintenance, and automobile news." },
                            { text: message }
                        ]
                    }
                ]
            })
        }
    );
    return await response.json();
}

// Helper: Call OpenAI API
async function callOpenAI(message) {
    const response = await fetch("https://api.openai.com/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${OPENAI_API_KEY}`
        },
        body: JSON.stringify({
            model: "gpt-3.5-turbo",
            messages: [
                { role: "system", content: "You are CarWay AI, a friendly and knowledgeable assistant about cars, car buying, maintenance, and automobile news." },
                { role: "user", content: message }
            ]
        })
    });
    const data = await response.json();
    return { reply: data.choices?.[0]?.message?.content || "I'm having trouble responding right now." };
}

// Main route
app.post("/chat", async (req, res) => {
    const { message } = req.body;

    try {
        // 1️⃣ Try Gemini Pro
        let data = await callGemini("gemini-1.5-pro", message);
        console.log("=== Gemini Pro Response ===", JSON.stringify(data, null, 2));

        if (data.candidates?.length) {
            return res.json({ reply: data.candidates[0].content.parts[0].text });
        }

        // 2️⃣ Fallback to Gemini Flash with up to 2 retries
        if (data.error) {
            console.log("⚠️ Pro failed, trying Flash...");
            for (let attempt = 1; attempt <= 2; attempt++) {
                await wait(1500); // wait 1.5 sec before retry
                data = await callGemini("gemini-1.5-flash", message);
                console.log(`=== Gemini Flash Attempt ${attempt} ===`, JSON.stringify(data, null, 2));
                if (data.candidates?.length) {
                    return res.json({ reply: data.candidates[0].content.parts[0].text });
                }
            }
        }

        // 3️⃣ If still failing → use OpenAI
        if (OPENAI_API_KEY) {
            console.log("⚠️ Both Gemini models failed, switching to OpenAI...");
            const openAIResponse = await callOpenAI(message);
            return res.json(openAIResponse);
        }

        // 4️⃣ If all fail → friendly fallback
        res.json({ reply: "🚗 I'm having trouble reaching my brain right now. Please try again in a moment!" });

    } catch (error) {
        console.error("Server error:", error);
        res.status(500).json({ reply: "⚠️ My circuits are overheating! Please try again later." });
    }
});

app.listen(3000, () => console.log("🚗 CarWay AI backend running on port 3000"));



/*
import express from "express";
import fetch from "node-fetch";
import bodyParser from "body-parser";
import cors from "cors";

const app = express();
app.use(cors());
app.use(bodyParser.json());

// List of Gemini API keys
const GEMINI_KEYS = ["AIzaSyBWcssT_u7oqTFccaCY6ne_5JEfxGfXsDw", "AIzaSyBZr8ioVL054aryhZukBdjjIhiXrEoyZIg", "AIzaSyAmfogAmGuftxYaxBkepZL3r2AcZBKgM70"];
let keyStatus = GEMINI_KEYS.map(() => ({ available: true, resetTimer: null }));

const OPENAI_API_KEY = "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";

// Configurable reset time (in milliseconds)
const KEY_RESET_TIME_MS = 5 * 60 * 1000; // 5 minutes.

// Helper: delay
function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Helper: get next available Gemini key
function getAvailableGeminiKey() {
    const availableIndex = keyStatus.findIndex(k => k.available);
    if (availableIndex === -1) return null; // no keys available
    return { key: GEMINI_KEYS[availableIndex], index: availableIndex };
}

// Helper: mark key as exhausted and auto-reset after configured time
function exhaustKey(index) {
    keyStatus[index].available = false;
    if (keyStatus[index].resetTimer) clearTimeout(keyStatus[index].resetTimer);
    keyStatus[index].resetTimer = setTimeout(() => {
        console.log(`🔄 Gemini key ${GEMINI_KEYS[index]} reset and available again.`);
        keyStatus[index].available = true;
    }, KEY_RESET_TIME_MS);
}

// Helper: Call Gemini API
async function callGemini(model, message, key) {
    const response = await fetch(
        `https://generativelanguage.googleapis.com/v1/models/${model}:generateContent?key=${key}`,
        {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                contents: [
                    {
                        parts: [
                            { text: "You are CarWay AI, a friendly and knowledgeable assistant about cars, car buying, maintenance, and automobile news." },
                            { text: message }
                        ]
                    }
                ]
            })
        }
    );
    return await response.json();
}

// Helper: Call OpenAI API
async function callOpenAI(message) {
    const response = await fetch("https://api.openai.com/v1/chat/completions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${OPENAI_API_KEY}`
        },
        body: JSON.stringify({
            model: "gpt-3.5-turbo",
            messages: [
                { role: "system", content: "You are CarWay AI, a friendly and knowledgeable assistant about cars, car buying, maintenance, and automobile news." },
                { role: "user", content: message }
            ]
        })
    });
    const data = await response.json();
    return { reply: data.choices?.[0]?.message?.content || "I'm having trouble responding right now." };
}

// Main route
app.post("/chat", async (req, res) => {
    const { message } = req.body;

    try {
        let data;

        // Try Gemini Pro first
        let gemini = getAvailableGeminiKey();
        while (gemini) {
            data = await callGemini("gemini-1.5-pro", message, gemini.key);
            console.log("=== Gemini Pro Response ===", JSON.stringify(data, null, 2));

            if (data.candidates?.length) {
                return res.json({ reply: data.candidates[0].content.parts[0].text });
            }

            // If quota exceeded, mark key as exhausted and try next
            if (data.error && data.error.code === 429) {
                console.log(`⚠️ Gemini key ${gemini.key} exhausted. Skipping to next key.`);
                exhaustKey(gemini.index);
                gemini = getAvailableGeminiKey();
            } else {
                break; // other error, move to Flash
            }
        }

        // Fallback to Gemini Flash with retries and smart rotation
        for (let attempt = 1; attempt <= 2; attempt++) {
            await wait(1500);
            gemini = getAvailableGeminiKey();
            if (!gemini) break; // no available keys
            data = await callGemini("gemini-1.5-flash", message, gemini.key);
            console.log(`=== Gemini Flash Attempt ${attempt} ===`, JSON.stringify(data, null, 2));
            if (data.candidates?.length) {
                return res.json({ reply: data.candidates[0].content.parts[0].text });
            }
            if (data.error && data.error.code === 429) {
                console.log(`⚠️ Gemini key ${gemini.key} exhausted. Skipping to next key.`);
                exhaustKey(gemini.index);
            }
        }

        // If all Gemini keys fail → OpenAI
        if (OPENAI_API_KEY) {
            console.log("⚠️ All Gemini keys failed, switching to OpenAI...");
            const openAIResponse = await callOpenAI(message);
            return res.json(openAIResponse);
        }

        // Friendly fallback
        res.json({ reply: "🚗 I'm having trouble reaching my brain right now. Please try again in a moment!" });

    } catch (error) {
        console.error("Server error:", error);
        res.status(500).json({ reply: "⚠️ My circuits are overheating! Please try again later." });
    }
});

app.listen(3000, () => console.log("🚗 CarWay AI backend running on port 3000"));
*/