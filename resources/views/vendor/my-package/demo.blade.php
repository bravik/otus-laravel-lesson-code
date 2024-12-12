@php
    /**
     * @var string $greetings
     * @var string $backgroundColor
     */
@endphp
<html>
<head>
    <title>Example Package</title>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            font-family: 'PT Sans Caption', serif;
            font-size: 42px;
        }
    </style>
</head>
<body style="background-color: {{$backgroundColor}}">
<h1>Overriden</h1>
<h1>{{$greetings}}</h1>
</body>
</html>
