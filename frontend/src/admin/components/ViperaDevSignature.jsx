export default function ViperaDevSignature({ asset = 'https://viperadev.com/ViperaDev-Logo.png' }) {
  return (
    <div className="vipera-signature">
      <span>Powered by</span>
      <img src={asset} alt="ViperaDev" />
    </div>
  );
}
