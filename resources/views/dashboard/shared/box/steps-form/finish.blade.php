<h3>{{ $step['label'] }}</h3>
<section>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="process[scanned]" value="1" @if($process->scanned) checked @endif> Deseja receber o seu documento digitalizado?
        </label>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="process[post_office]" value="1" @if($process->post_office) checked @endif> Deseja receber o seu documento por correio?
        </label>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="process[sign_digital_certificate]" value="1" @if($process->sign_digital_certificate) checked @endif> Deseja que o seu processo seja assinado 100% digitalmente, através do Certificado Digital?
        </label>
    </div>
</section>
