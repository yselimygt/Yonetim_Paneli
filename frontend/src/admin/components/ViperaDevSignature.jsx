export default function ViperaDevSignature({ asset = '/public/assets/sponsor/ViperaDev.png' }) {
  return (
    <div className="vipera-signature">
      <span>Powered by</span>
      <img src={asset} alt="ViperaDev" />
    </div>
  );
}
