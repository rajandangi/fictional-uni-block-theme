/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store('create-block', {
    actions: {
        guessAttempt() {
            const context = getContext();

            if (context.showCorrectMessage === true) {
                return;
            }

            const clickedAnswer = context.item;
            const answerIndex = context.answers.indexOf(clickedAnswer);

            if (answerIndex === context.correctAnswer) {
                context.showCorrectMessage = true;
                setTimeout(() => {
                    context.delyShowIcon = true;
                }, 1000);
            } else {
                context.showIncorrectMessage = true;
                setTimeout(() => {
                    context.showIncorrectMessage = false;
                }, 2600);
            }
        },
    },
    callbacks: {
        showCheckIcon: () => {
            const context = getContext();
            return context.delyShowIcon &&
                context.showCorrectMessage &&
                context.correctAnswer === context.answers.indexOf(context.item);
        },
        showCrossIcon: () => {
            const context = getContext();
            return context.delyShowIcon &&
                context.showCorrectMessage &&
                context.correctAnswer !== context.answers.indexOf(context.item);
        },
    },
});
