import React, { useEffect, useState, useRef } from "react";

const slidesDefault = [
    {
        title: "Selamat Datang di Alwi College",
        subtitle: "Membangun Masa Depan Cerah Melalui Pendidikan Berkualitas",
        bg: "linear-gradient(135deg,#667eea 0%,#764ba2 100%)",
    },
    {
        title: "Raih Prestasi Terbaik",
        subtitle: "Belajar dengan Teknologi dan Metode Pembelajaran Modern",
        bg: "linear-gradient(135deg,#16a34a 0%,#0891b2 100%)",
    },
    {
        title: "Bergabunglah dengan Komunitas",
        subtitle: "Mari tumbuh bersama dalam lingkungan belajar yang positif",
        bg: "linear-gradient(135deg,#7c3aed 0%,#ec4899 100%)",
    },
];

export default function Hero({ slides = slidesDefault, interval = 5000 }) {
    const [index, setIndex] = useState(0);
    const timer = useRef(null);

    useEffect(() => {
        start();
        return stop;
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [index]);

    function start() {
        stop();
        timer.current = setTimeout(() => {
            setIndex((i) => (i + 1) % slides.length);
        }, interval);
    }

    function stop() {
        if (timer.current) clearTimeout(timer.current);
    }

    function goTo(i) {
        setIndex(i);
    }

    return (
        <section className="mb-8">
            <div className="relative w-full rounded-2xl overflow-hidden shadow-lg">
                <div className="w-full aspect-[16/5] rounded-2xl overflow-hidden relative">
                    {slides.map((s, i) => (
                        <div
                            key={i}
                            className={`absolute inset-0 transition-opacity duration-700 ${
                                i === index ? "opacity-100" : "opacity-0"
                            }`}
                            style={{ background: s.bg }}
                            onMouseEnter={stop}
                            onMouseLeave={start}
                        >
                            <div className="w-full h-full flex items-center justify-center">
                                <div className="text-center text-white px-8">
                                    <h3 className="text-3xl md:text-4xl font-bold mb-4">
                                        {s.title}
                                    </h3>
                                    <p className="text-lg md:text-xl">
                                        {s.subtitle}
                                    </p>
                                </div>
                            </div>
                        </div>
                    ))}

                    {/* Dots */}
                    <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        {slides.map((_, i) => (
                            <button
                                key={i}
                                aria-label={`Slide ${i + 1}`}
                                className={`w-3 h-3 rounded-full ${
                                    i === index
                                        ? "bg-white opacity-100"
                                        : "bg-white opacity-50"
                                }`}
                                onClick={() => goTo(i)}
                            />
                        ))}
                    </div>

                    {/* Arrows */}
                    <button
                        onClick={() =>
                            setIndex(
                                (i) => (i - 1 + slides.length) % slides.length
                            )
                        }
                        className="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition"
                    >
                        <svg
                            className="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>
                    <button
                        onClick={() => setIndex((i) => (i + 1) % slides.length)}
                        className="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/30 hover:bg-white/50 text-white rounded-full p-2 transition"
                    >
                        <svg
                            className="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </section>
    );
}
