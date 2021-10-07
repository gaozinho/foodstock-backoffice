function startProgress(seconds, progressEnclosure){
    return $(progressEnclosure).progressBarTimer({ 
        autoStart: true, smooth: false, timeLimit: seconds, completeStyle: 'bg-primary', warningStyle: 'bg-primary', baseStyle: 'bg-primary'
    });
}

function refreshComponent(livewireListener, progressEnclosure, seconds){
    
    var progress = startProgress(seconds, progressEnclosure);
    console.log(progress);

    return setInterval(() => { 
        try{
            progress = startProgress(seconds);
            progress.reset();
            progress.start();
            Livewire.emit(livewireListener);
        }catch(e){
            location.reload();
        }
    }, seconds * 1000);
}